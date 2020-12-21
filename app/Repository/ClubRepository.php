<?php
namespace App\Repository;
use App\Model\Admin\Bank;
use App\Model\MembershipBonusDistributionHistory;
use App\Model\MembershipClub;
use App\Model\MembershipPlan;
use App\Model\MembershipTransactionHistory;
use App\Model\Wallet;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Null_;

class ClubRepository
{
    // transfer coin from wallet to club
    public function transferCoinToMembershipClub($request)
    {
        $response = ['success' => false, 'message' => __('Invalid request')];
        try {
            $checkWallet = Wallet::where(['id' => $request->wallet_id, 'user_id' => Auth::id()])->first();
            if (empty($checkWallet)) {
                $response = [
                    'success' => false,
                    'message' => __('Invalid wallet')
                ];
                return $response;
            }

            $small_plan = MembershipPlan::where(['status'=>STATUS_ACTIVE])->orderBy('amount','asc')->first();
            $checkMembership = MembershipClub::where('user_id', Auth::id())->first();
            if (empty($checkMembership) && ($request->amount < $small_plan->amount)) {
                $response = [
                    'success' => false,
                    'message' => __('For the first time you must transfer equal or greater than '). number_format($small_plan->amount,2).' '. settings('coin_name')
                ];
                return $response;
            }
            if ($checkWallet->balance < $request->amount) {
                $response = [
                    'success' => false,
                    'message' => __('Insufficient wallet balance')
                ];
                return $response;
            }
            $amount = $request->amount + get_blocked_coin(Auth::id());
            $current_plan = find_plan_by_amount($amount);
            $data=[];

            if (empty($checkMembership)) {
                $data['amount'] = $request->amount;
                $data['user_id'] = Auth::id();
                if (!empty($current_plan)) {
                    $data['plan_id'] = $current_plan->id;
                    $data['start_date'] = date('Y-m-d');
                    $data['end_date'] = date('Y-m-d', strtotime($data['start_date']. ' + '.$current_plan->duration.' days'));
                }
            } else {
                $data['amount'] = $checkMembership->amount + $request->amount;
                if (!empty($current_plan)) {
                    if ($current_plan->id != $checkMembership->plan_id) {
                        $data['plan_id'] = $current_plan->id;
                        $data['start_date'] = date('Y-m-d');
                        $data['end_date'] = date('Y-m-d', strtotime($data['start_date']. ' + '.$current_plan->duration.' days'));
                    }
                } else {
                    $data['plan_id'] = Null;
                    $data['start_date'] = Null;
                    $data['end_date'] = Null;
                }
            }

            if (!empty($checkMembership)) {
                $update = MembershipClub::where(['id' => $checkMembership->id])->update($data);
                if ($update) {
                    $checkWallet->decrement('balance',$request->amount);
                    $this->saveCoinTransferHistory($checkMembership->user_id,$checkMembership->id,$request->wallet_id,$request->amount,CREDIT);
                    $response = [
                        'success' => true,
                        'message' => __('Coin transfer to club successfully')
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => __('Failed to transfer')
                    ];
                }
            } else {
                $saveData= MembershipClub::create($data);
                if ($saveData) {
                    $checkWallet->decrement('balance',$request->amount);
                    $this->saveCoinTransferHistory($saveData->user_id,$saveData->id,$request->wallet_id,$request->amount,CREDIT);
                    $response = [
                        'success' => true,
                        'message' => __('Coin transfer to club successfully.')
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => __('Failed to transfer')
                    ];
                }
            }

        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage()
//                'message' => __('Something Went wrong !')
            ];
            return $response;
        }

        return $response;
    }

    // transfer coin from club to wallet
    public function transferCoinToMyWallet($request)
    {
        $response = ['success' => false, 'message' => __('Invalid request')];
        try {
            $checkWallet = Wallet::where(['id' => $request->wallet_id, 'user_id' => Auth::id()])->first();
            if (empty($checkWallet)) {
                $response = [
                    'success' => false,
                    'message' => __('Invalid wallet')
                ];
                return $response;
            }
            $checkMembership = MembershipClub::where('user_id', Auth::id())->first();
            if (empty($checkMembership)) {
                $response = [
                    'success' => false,
                    'message' => __('Currently you have no membership')
                ];
                return $response;
            }
            if ($checkMembership->amount < $request->amount) {
                $response = [
                    'success' => false,
                    'message' => __('Insufficient club balance')
                ];
                return $response;
            }

            $amount = $checkMembership->amount - $request->amount ;
            $current_plan = find_plan_by_amount($amount);

            $data['amount'] = $amount;
            if (!empty($current_plan)) {
                if ($current_plan->id != $checkMembership->plan_id) {
                    $data['plan_id'] = $current_plan->id;
                    $data['start_date'] = date('Y-m-d');
                    $data['end_date'] = date('Y-m-d', strtotime($data['start_date']. ' + '.$current_plan->duration.' days'));
                }
            } else {
                $data['plan_id'] = Null;
                $data['start_date'] = Null;
                $data['end_date'] = Null;
            }

            $update = MembershipClub::where(['id' => $checkMembership->id])->update($data);
            if ($update) {
                $checkWallet->increment('balance',$request->amount);
                $this->saveCoinTransferHistory($checkMembership->user_id,$checkMembership->id,$request->wallet_id,$request->amount,DEBIT);
                $response = [
                    'success' => true,
                    'message' => __('Coin return to wallet successfully')
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => __('Failed to transfer')
                ];
            }

        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage()
//                'message' => __('Something Went wrong !')
            ];
            return $response;
        }
        return $response;
    }

    // save coin transfer history
    public function saveCoinTransferHistory($user_id,$club_id,$wallet_id,$amount,$type)
    {
        DB::beginTransaction();
        try {
            MembershipTransactionHistory::create([
                'user_id' => $user_id,
                'club_id' => $club_id,
                'wallet_id' => $wallet_id,
                'amount' => $amount,
                'type' => $type,
            ]);
            $response = [
                'success' => true,
                'message' => __('History saved successfully')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $response;
        }
        DB::commit();
        return $response;
    }

    // save plan details
    public function saveClubPlan($request)
    {
        $response = [
            'success' => false,
            'message' => __('Something went wrong')
        ];
        DB::beginTransaction();
        try {
            $data = [
                'plan_name' => $request->plan_name,
                'duration' => $request->duration,
                'amount' => $request->amount,
                'bonus_type' => $request->bonus_type,
                'bonus' => $request->bonus,
                'status' => $request->status,
                'description' => $request->description,
            ];
            if (isset($request->edit_id)) {
                $plan = MembershipPlan::where('id', $request->edit_id)->first();
            }
            if (isset($request->image)) {
                $old_img = '';
                if(isset($plan) && (!empty($plan->image))) {
                    $old_img = $plan->image;
                }
                $data['image'] = uploadFile($request['image'],IMG_PATH,$old_img);
            }
            if (!empty($request->edit_id)) {
                if (isset($plan)) {
                    $plan->update($data);
                    $response = [
                        'success' => true,
                        'message' => __('Plan updated successfully')
                    ];
                }
            } else {
                MembershipPlan::create($data);
                $response = [
                    'success' => true,
                    'message' => __('Plan saved successfully')
                ];
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $response;
        }
        DB::commit();
        return $response;
    }

    // club bonus distribution process
    public function clubBonusDistributionProcess()
    {
        $response = [
            'success' => false,
            'message' => __('Something went wrong')
        ];
        DB::beginTransaction();
        try {
            Log::info('distribution called');
            $minimum_bonus_amount = 0;
            $first_plan = MembershipPlan::orderBy('amount','asc')->first();
            if (isset($first_plan)) {
                $minimum_bonus_amount = $first_plan->amount;
            }
            $members = MembershipClub::where('status', STATUS_ACTIVE)
                ->where('plan_id','!=', Null)
                ->where('amount','>=', $minimum_bonus_amount)
                ->where('end_date', date('Y-m-d'))
                ->get();
            if (isset($members[0])) {
                Log::info('member found');
                Log::info('start distribution');
                foreach ($members as $member) {
                    Log::info($member->user_id);
                    $find_plan = MembershipPlan::where('id',$member->plan_id)->first();
                    if (isset($find_plan)) {
                        $wallet = Wallet::where(['user_id'=>$member->user_id, 'is_primary'=> STATUS_SUCCESS])->first();
                        $bonus_percentage = plan_bonus_percentage($find_plan->bonus_type,$find_plan->bonus,$find_plan->amount);
                        $bonus = calculate_plan_bonus($bonus_percentage,$member->amount);
                        $bonus_distribution = MembershipBonusDistributionHistory::where(['user_id' => $member->user_id, 'distribution_date'=>$member->end_date])->first();

                        if (empty($bonus_distribution)) {
                            $wallet->increment('balance',$bonus);
                            $member->update([
                                'start_date'=>date('Y-m-d'),
                                'end_date'=>date('Y-m-d', strtotime(date('Y-m-d'). ' + '.$find_plan->duration.' days'))
                            ]);
                            $save = $this->saveBonusDistributionHistory($member->user_id,$wallet->id,$member->plan_id,$member->id,date('Y-m-d'),$bonus,$find_plan->bonus,$find_plan->bonus_type);
                        }
                    }
                }
                Log::info('end distribution');
            } else {
                Log::info('no member available for bonus');
                $response = [
                    'success' => false,
                    'message' => __('No member found')
                ];
                return  $response;
            }


            $response = [
                'success' => true,
                'message' => __('Bonus distributed successfully')
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $response;
        }

        DB::commit();
        return $response;
    }

    // save bonus distribution history
    public function saveBonusDistributionHistory($user_id,$wallet_id,$plan_id,$membership_id,$distribution_date,$bonus_amount,$plan_current_bonus,$bonus_type)
    {
        try {
            $data = [
              'user_id' => $user_id,
              'wallet_id' => $wallet_id,
              'plan_id' => $plan_id,
              'membership_id' => $membership_id,
              'distribution_date' => $distribution_date,
              'bonus_amount' => $bonus_amount,
              'plan_current_bonus' => $plan_current_bonus,
              'bonus_type' => $bonus_type,
              'status' => STATUS_SUCCESS,
            ];

            $save = MembershipBonusDistributionHistory::create($data);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}

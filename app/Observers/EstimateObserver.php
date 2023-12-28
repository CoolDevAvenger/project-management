<?php

namespace App\Observers;

use File;
use Carbon\Carbon;
use App\Helper\Files;
use App\Models\Invoice;
use App\Models\Estimate;
use App\Models\Expense;
use Illuminate\Support\Str;
use App\Models\EstimateItem;
use App\Models\InvoiceItems;
use App\Models\Notification;
use App\Models\UniversalSearch;
use App\Events\NewEstimateEvent;
use App\Models\ExpensesCategory;
use App\Models\EstimateItemImage;
use App\Traits\UnitTypeSaveTrait;
use App\Events\EstimateAcceptedEvent;
use App\Events\EstimateDeclinedEvent;

class EstimateObserver
{

    use UnitTypeSaveTrait;

    public function saving(Estimate $estimate)
    {

        $this->unitType($estimate);

        if (!isRunningInConsoleOrSeeding()) {

            if (\user()) {
                $estimate->last_updated_by = user()->id;
            }

            if (request()->has('calculate_tax')) {
                $estimate->calculate_tax = request()->calculate_tax;
            }
        }

    }

    public function creating(Estimate $estimate)
    {
        $estimate->hash = md5(microtime());

        if (\user()) {
            $estimate->added_by = user()->id;
        }

        if (request()->type && (request()->type == 'save' || request()->type == 'draft')) {
            $estimate->send_status = 0;
        }

        if (request()->type == 'draft') {
            $estimate->status = 'draft';
        }

        if (company()) {
            $estimate->company_id = company()->id;
        }
    }

    public function created(Estimate $estimate)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if (!empty(request()->item_name)) {
                $product_category_id = request()->product_category_id;
                $product_category_name = request()->product_category_name;
                $product_sub_category_id = request()->product_sub_category_id;
                $product_sub_category_name = request()->product_sub_category_name;
                $hsn_sac_code = request()->hsn_sac_code;
                $item_id = request()->item_id;
                $product_id = request()->product_id;
                $product_name = request()->product_name;
                $product_unit_id = request()->product_unit_id;
                $product_unit_name = request()->product_unit_name;
                $service_margin_percent = request()->service_margin_percent;
                $cost = request()->cost;
                $margin = request()->margin;
                $itemsSummary = request()->item_summary;
                $cost_per_item = request()->cost_per_item;
                $hsn_sac_code = request()->hsn_sac_code;
                $quantity = request()->quantity;
                $amount = request()->amount;
                $tax = request()->taxes;
                $invoice_item_image = request()->invoice_item_image;
                $invoice_item_image_delete = request()->invoice_item_image_delete;
                $invoice_item_image_url = request()->invoice_item_image_url;
                $invoiceOldImage = request()->image_id;

                foreach (request()->item_name as $key => $item) :

                    $expense_item_name = request()->{'expense_item_name'.$key};
                    $expense_price = request()->{'expense_price'.$key};
                    $expense_margin_percent = request()->{'expense_margin_percent'.$key};
                    $expense_amount = request()->{"expense_amount".$key};
                    $expense_cost = request()->{'expense_cost'.$key};
                    $expense_margin = request()->{'expense_margin'.$key};
                    $expense_category_id = request()->{'expense_category_id'.$key};
                    $expense_item_id = request()->{'expense_item_id'.$key};
                    $expnese_tax = request()->{'expnese_tax'.$key};
                    $expense_tax_amount = request()->{'expense_tax_amount'.$key};
                    if (!is_null($item)) {
                        // $estExpense = Expense::findOrFail($expense_item_id[$key]);
                        if(!is_null($expense_item_id[$key]) && $expense_item_id[$key] > 0){
                            $estExpense = Expense::findOrFail($expense_item_id[$key]);
                            if(!is_null($estExpense)){
                                $expense = new Expense();
                                $expense->item_name = $estExpense->item_name;
                                $expense->purchase_date = now(company()->timezone);
                                $expense->price = $estExpense->price;
                                $expense->currency_id = $estExpense->currency_id;
                                $expense->category_id = $estExpense->category_id;
                                $expense->user_id = user()->id;
                                $expense->default_currency_id = company()->currency_id;
                                $expense->description = $estExpense->description;
                                $expense->approver_id = null;
                                $expense->status = 'pending';
                                $expense->save();
                            }
                        }
                        $estimateItem = EstimateItem::create(
                            [
                                'estimate_id' => $estimate->id,
                                'item_id' => $product_id[$key],
                                'item_name' => $item,
                                'item_summary' => $itemsSummary[$key],
                                'type' => 'item',
                                'hsn_sac_code' => (isset($hsn_sac_code[$key]) && !is_null($hsn_sac_code[$key])) ? $hsn_sac_code[$key] : null,
                                'quantity' => $quantity[$key],

                                'product_category_id' => $product_category_id[$key],
                                'product_category_name' => $product_category_name[$key],
                                'product_sub_category_id' => $product_sub_category_id[$key],
                                'product_sub_category_name' => $product_sub_category_name[$key],
                                'service_margin_percent' => $service_margin_percent[$key],

                                'unit_id' => $product_unit_id[$key],
                                'unit_name' => $product_unit_name[$key],
                                'item_price' =>round($cost_per_item[$key], 2),

                                'unit_price' => round($cost_per_item[$key], 2),
                                'amount' => round($amount[$key], 2),
                                'cost' => $cost[$key],
                                'margin' => $margin[$key],
                                'expense_category_id' => json_encode($expense_category_id),
                                'expense_category_name' => ExpensesCategory::where('id', $expense_category_id[$key])->pluck('category_name')->first(),

                                'expense_item_id' => json_encode($expense_item_id),
                                'expense_item_name' => json_encode($expense_item_name),
                                'expense_price' => json_encode($expense_price),
                                'expense_margin_percent' => json_encode($expense_margin_percent),
                                'expense_amount' => json_encode($expense_amount),
                                'expense_cost' => json_encode($expense_cost),
                                'expense_margin' => json_encode($expense_margin),
                                'expnese_tax' => json_encode($expnese_tax),
                                'expense_tax_amount' => json_encode($expense_tax_amount),
                                'taxes' => $tax ? array_key_exists($key, $tax) ? json_encode($tax[$key]) : null : null
                            ]
                        );

                        /* Invoice file save here */

                        if ((isset($invoice_item_image[$key]) && $invoice_item_image[$key] != 'yes') || isset($invoice_item_image_url[$key]))
                        {
                            EstimateItemImage::create(
                                [
                                    'estimate_item_id' => $estimateItem->id,
                                    'filename' => !isset($invoice_item_image_url[$key]) ? $invoice_item_image[$key]->getClientOriginalName() : '',
                                    'hashname' => !isset($invoice_item_image_url[$key]) ? Files::uploadLocalOrS3($invoice_item_image[$key], EstimateItemImage::FILE_PATH . '/' . $estimateItem->id . '/') : '',
                                    'size' => !isset($invoice_item_image_url[$key]) ? $invoice_item_image[$key]->getSize() : '',
                                    'external_link' => isset($invoice_item_image_url[$key]) ? $invoice_item_image_url[$key] : ''
                                ]
                            );

                        }

                        $image = true;

                        if(isset($invoice_item_image_delete[$key]))
                        {
                            $image = false;
                        }

                        if($image && (isset(request()->image_id[$key]) && $invoiceOldImage[$key] != ''))
                        {
                            $estimateOldImg = EstimateItemImage::with('item')->where('id', request()->image_id[$key])->first();

                            $this->duplicateImageStore($estimateOldImg, $estimateItem);
                        }

                    }

                endforeach;
            }



            if (request()->type != 'save' && request()->type != 'draft') {
                event(new NewEstimateEvent($estimate));
            }
        }
    }

    public function updated(Estimate $estimate)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if ($estimate->status == 'declined') {
                event(new EstimateDeclinedEvent($estimate));
            }
            elseif ($estimate->status == 'accepted') {
                event(new EstimateAcceptedEvent($estimate));
            }
        }
    }

    public function deleting(Estimate $estimate)
    {
        $universalSearches = UniversalSearch::where('searchable_id', $estimate->id)->where('module_type', 'estimate')->get();

        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }

        $notifyData = ['App\Notifications\NewEstimate'];
        \App\Models\Notification::deleteNotification($notifyData, $estimate->id);

    }

    /**
     * duplicateImageStore
     *
     * @param  mixed $estimateOldImg
     * @param  mixed $estimateItem
     * @return void
     */
    public function duplicateImageStore($estimateOldImg, $estimateItem)
    {
        if(!is_null($estimateOldImg)) {

            $file = new EstimateItemImage();

            $file->estimate_item_id = $estimateItem->id;

            $fileName = Files::generateNewFileName($estimateOldImg->filename);

            Files::copy(EstimateItemImage::FILE_PATH . '/' . $estimateOldImg->item->id . '/' . $estimateOldImg->hashname, EstimateItemImage::FILE_PATH . '/' . $estimateItem->id . '/' . $fileName);

            $file->filename = $estimateOldImg->filename;
            $file->hashname = $fileName;
            $file->size = $estimateOldImg->size;
            $file->save();

        }
    }

}

<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Models\StructureCost;
use App\Models\CustomField;
use App\Models\CustomFieldGroup;
use App\DataTables\BaseDataTable;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;

class StructureCostDataTable extends BaseDataTable
{

    protected $firstEstimate;
    private $addEstimatePermission;
    private $editEstimatePermission;
    private $deleteEstimatePermission;
    private $viewEstimatePermission;

    public function __construct()
    {
        parent::__construct();
        $this->viewEstimatePermission = user()->permission('view_estimates');
        $this->addEstimatePermission = user()->permission('add_estimates');
        $this->editEstimatePermission = user()->permission('edit_estimates');
        $this->deleteEstimatePermission = user()->permission('delete_estimates');
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $firstEstimate = $this->firstEstimate;

        $datatables = datatables()->eloquent($query);
        $datatables->addIndexColumn();
        $datatables->addColumn('action', function ($row) use ($firstEstimate) {

            $action = '<div class="task_view">

            <div class="dropdown">
                <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                    id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-options-vertical icons"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';

            $action .= '<a class="dropdown-item " href="' . route('structureCost.edit', [$row->id]) . '">
                <i class="fa fa-edit mr-2"></i>
                ' . trans('app.edit') . '
            </a>';

            if ($firstEstimate->id == $row->id) {
                if (
                    $this->deleteEstimatePermission == 'all'
                    || ($this->deleteEstimatePermission == 'added' && $row->added_by == user()->id)
                    || ($this->deleteEstimatePermission == 'owned' && $row->client_id == user()->id)
                    || ($this->deleteEstimatePermission == 'both' && ($row->client_id == user()->id || $row->added_by == user()->id))
                ) {

                    $action .= '<a class="dropdown-item delete-table-row" href="javascript:;" data-estimate-id="' . $row->id . '">
                        <i class="fa fa-trash mr-2"></i>
                        ' . trans('app.delete') . '
                    </a>';
                }
            }

            $action .= '</div>
            </div>
        </div>';

            return $action;
        });
        $datatables->addColumn('original_structure_cost_number', function ($row) {
            return $row->structure_cost_no;
        });

        $datatables->editColumn('total', function ($row) {
            return currency_format($row->total, $row->currencyId);
        });
        $datatables->editColumn(
            'created_at',
            function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat($this->company->date_format);
            }
        );
        $datatables->rawColumns(['name', 'action', 'year', 'original_structure_cost_number']);
        $datatables->removeColumn('currency_symbol');
        $datatables->removeColumn('client_id');

        // Custom Fields For export
        CustomField::customFieldData($datatables, StructureCost::CUSTOM_FIELD_MODEL);

        return $datatables;
    }

    public function ajax()
    {
        return $this->dataTable($this->query())
            ->make(true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $request = $this->request();

        $this->firstEstimate = StructureCost::orderBy('id', 'desc')->first();
        $model = StructureCost::with('company:id')
            ->select([
                'id',
                'structure_cost_no',
                'company_id',
                'year',
                'total_structure_cost as total',
                'currency_id',
                'created_at'
            ]);

        if ($request->searchText != '') {
            $model->where(function ($query) {
                $query->where('year', 'like', '%' . request('searchText') . '%')
                    ->orWhere('id', 'like', '%' . request('searchText') . '%')
                    ->orWhere('structure_cost_no', 'like', '%' . request('searchText') . '%')
                    ->orWhere('total', 'like', '%' . request('searchText') . '%');
            });
        }

        if ($this->viewEstimatePermission == 'added') {
            $model->where('added_by', user()->id);
        }

        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->setBuilder('invoices-table')
            ->parameters([
                'initComplete' => 'function () {
                    window.LaravelDataTables["invoices-table"].buttons().container()
                    .appendTo( "#table-actions")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
            ])
            ->buttons(Button::make(['extend' => 'excel', 'text' => '<i class="fa fa-file-export"></i> ' . trans('app.exportExcel')]));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $data = [
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'visible' => false],
            __('app.id') => ['data' => 'id', 'name' => 'id', 'title' => __('app.id'), 'visible' => false],
            __('app.structureCostNo') . '#' => ['data' => 'original_structure_cost_number', 'name' => 'original_structure_cost_number', 'title' => __('app.structureCostNo')],
            __('app.year') => ['data' => 'year', 'name' => 'year', 'title' => __('app.year')],
            __('app.total') => ['data' => 'total', 'name' => 'total', 'title' => __('app.total')],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-right pr-20')
        ];

        return array_merge($data, CustomFieldGroup::customFieldsDataMerge(new StructureCost()));

    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Structure_Cost_' .now()->format('Y-m-d-H-i-s');
    }

}

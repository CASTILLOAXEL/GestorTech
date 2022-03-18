<?php

namespace App\DataTables;

use App\Models\Role;
use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class UserDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->addColumn('action', function($User){
            $id = $User->id;
            return view('admin.users.datatables_actions',compact('User','id'));
        })->editColumn('avatar',function (User $user){

            return "<img src='{$user->thumb}' alt='' width='50' height='50'>";

        })->editColumn('roles',function (User $user){

            return view('admin.users.partials.roles',compact('user'));

        })->rawColumns(['action','avatar','roles']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $query = $model->newQuery()->with(['roles','media']);

        //si el usuario no puede ver a todos los usuarios
        if (auth()->user()->cannot('ver todos los usuarios')){

            //excluir los roles dev y super
            $query->whereDoesntHave('roles',function ($q){
                $q->whereIn('id',[Role::DEVELOPER,Role::SUPERADMIN]);
            });
        }

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '15%', 'printable' => false])
            ->parameters([
                'dom'     => 'Bfltrip',
                'order'   => [[1, 'asc']],
                'language' => ['url' => asset('js/SpanishDataTables.json')],
                //'scrollX' => false,
                'responsive' => true,

            ])
            ->buttons(
                Button::make('create'),
                Button::make('export'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'avatar' => ['searchable' => false],
            'id',
            'username',
            'name',
            'email',
            'provider',
            'roles' => ['searchable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'usersdatatable_' . time();
    }
}

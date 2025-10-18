<?php

namespace App\Datatables;

class DataTables
{
    protected $params;

    public function prepareRequest($request) {

        $perPage = $request->input('length', 10);
        $start = $request->input('start', 0);
        $page = ($start / $perPage) + 1;

        $search = $request->input('search', '');

        $this->params = [
            'per_page' => $perPage,
            'page' => $page,
        ];

        if (!empty($search)) {
            $this->params['search'] = $search;
        }   
    }
}

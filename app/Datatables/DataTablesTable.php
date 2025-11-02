<?php

namespace App\Datatables;

class DataTablesTable
{
    /**
     * Prepare the parameters for the DataTable request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function prepareRequest($request) : array {

        $perPage = $request->input('length', 10);
        $start = $request->input('start', 0);
        $page = ($start / $perPage) + 1;

        $searchData = $request->input('search', []);
        $search = is_array($searchData) ? ($searchData['value'] ?? '') : $searchData;

        $params = [
            'per_page' => $perPage,
            'page' => $page,
        ];

        if (!empty($search) && trim($search) !== '') {
            $params['search'] = trim($search);
        }   

        return $params;
    }
    
}

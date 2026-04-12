<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Retorna todas las mesas para la UI del POS
     */
    public function index()
    {
        // Traemos las mesas de PostgreSQL
        $tables = Table::orderBy('number', 'asc')->get();
        return response()->json($tables);
    }

    /**
     * Actualiza el estado de una mesa (libre/ocupada/reserved)
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,occupied,reserved',
        ]);

        $table = Table::findOrFail($id);
        $table->status = $validated['status'];
        $table->save();

        return response()->json([
            'message' => 'Estado de mesa actualizado',
            'table' => $table
        ]);
    }

    /**
     * Libera una mesa (marca como disponible)
     */
    public function free($id)
    {
        $table = Table::findOrFail($id);
        $table->status = 'available';
        $table->save();

        return response()->json([
            'message' => 'Mesa liberada',
            'table' => $table
        ]);
    }
}
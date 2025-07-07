<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketCategorie;
use Illuminate\Http\Request;

class TicketCategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(TicketCategorie::orderBy('nom')->get());
    }
}

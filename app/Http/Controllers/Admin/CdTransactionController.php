<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CdAccTransaction;
use App\Models\CdMaster;
use Illuminate\Http\Request;

class CdTransactionController extends Controller
{
    /**
     * Display a listing of transactions for a specific CD account.
     */
    public function index($cdAccountId)
    {
        $cdAccount = CdMaster::with('company')->findOrFail($cdAccountId);
        $transactions = CdAccTransaction::where('cd_ac_id', $cdAccountId)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate summary
        $totalCredits = CdAccTransaction::where('cd_ac_id', $cdAccountId)
            ->where('credit_type', 'CREDIT')
            ->sum('transaction_amount');

        $totalDebits = CdAccTransaction::where('cd_ac_id', $cdAccountId)
            ->where('credit_type', 'DEBIT')
            ->sum('transaction_amount');

        $netBalance = $totalCredits - $totalDebits;

        return view('admin.cd_account.transactions.index', compact(
            'cdAccount', 'transactions', 'totalCredits', 'totalDebits', 'netBalance'
        ));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create($cdAccountId)
    {
        $cdAccount = CdMaster::with('company')->findOrFail($cdAccountId);
        return view('admin.cd_account.transactions.create', compact('cdAccount'));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request, $cdAccountId)
    {
        $cdAccount = CdMaster::findOrFail($cdAccountId);

        $request->validate([
            'credit_type' => 'required|in:DEBIT,CREDIT',
            'transaction_amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240'
        ]);

        $transaction = new CdAccTransaction();
        $transaction->cd_ac_id = $cdAccountId;
        $transaction->credit_type = $request->credit_type;
        $transaction->transaction_amount = $request->transaction_amount;
        $transaction->transaction_date = $request->transaction_date;
        $transaction->description = $request->description;

        // Handle document upload
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Create transaction folder
            $folderPath = 'transactions/' . $cdAccount->cd_ac_no;
            if (!file_exists(public_path($folderPath))) {
                mkdir(public_path($folderPath), 0755, true);
            }

            $file->move(public_path($folderPath), $fileName);
            $transaction->document = $folderPath . '/' . $fileName;
        }

        $transaction->save();

        // Update CD account current balance incrementally
        if ($transaction->credit_type === 'CREDIT') {
            $cdAccount->current_balance = ($cdAccount->current_balance ?? 0) + $transaction->transaction_amount;
        } else { // DEBIT
            $cdAccount->current_balance = ($cdAccount->current_balance ?? 0) - $transaction->transaction_amount;
        }
        $cdAccount->save();

        return redirect()->route('admin.cd_account.transactions', $cdAccountId)
            ->with('success', 'Transaction added successfully!');
    }

    /**
     * Display the specified transaction.
     */
    public function show($cdAccountId, $id)
    {
        $cdAccount = CdMaster::with('company')->findOrFail($cdAccountId);
        $transaction = CdAccTransaction::where('cd_ac_id', $cdAccountId)->findOrFail($id);

        return view('admin.cd_account.transactions.show', compact('cdAccount', 'transaction'));
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit($cdAccountId, $id)
    {
        $cdAccount = CdMaster::with('company')->findOrFail($cdAccountId);
        $transaction = CdAccTransaction::where('cd_ac_id', $cdAccountId)->findOrFail($id);

        return view('admin.cd_account.transactions.edit', compact('cdAccount', 'transaction'));
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(Request $request, $cdAccountId, $id)
    {
        $cdAccount = CdMaster::findOrFail($cdAccountId);
        $transaction = CdAccTransaction::where('cd_ac_id', $cdAccountId)->findOrFail($id);

        $request->validate([
            'credit_type' => 'required|in:DEBIT,CREDIT',
            'transaction_amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240'
        ]);

        // Store original values for balance calculation
        $originalCreditType = $transaction->credit_type;
        $originalAmount = $transaction->transaction_amount;

        $transaction->credit_type = $request->credit_type;
        $transaction->transaction_amount = $request->transaction_amount;
        $transaction->transaction_date = $request->transaction_date;
        $transaction->description = $request->description;

        // Handle document upload
        if ($request->hasFile('document')) {
            // Delete old document if exists
            if ($transaction->document && file_exists(public_path($transaction->document))) {
                unlink(public_path($transaction->document));
            }

            $file = $request->file('document');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Create transaction folder
            $folderPath = 'transactions/' . $cdAccount->cd_ac_no;
            if (!file_exists(public_path($folderPath))) {
                mkdir(public_path($folderPath), 0755, true);
            }

            $file->move(public_path($folderPath), $fileName);
            $transaction->document = $folderPath . '/' . $fileName;
        }

        $transaction->save();

        // Update CD account current balance incrementally
        // First, revert the original transaction effect
        if ($originalCreditType === 'CREDIT') {
            $cdAccount->current_balance = ($cdAccount->current_balance ?? 0) - $originalAmount;
        } else { // DEBIT
            $cdAccount->current_balance = ($cdAccount->current_balance ?? 0) + $originalAmount;
        }

        // Then, apply the new transaction effect
        if ($transaction->credit_type === 'CREDIT') {
            $cdAccount->current_balance = ($cdAccount->current_balance ?? 0) + $transaction->transaction_amount;
        } else { // DEBIT
            $cdAccount->current_balance = ($cdAccount->current_balance ?? 0) - $transaction->transaction_amount;
        }
        $cdAccount->save();

        return redirect()->route('admin.cd_account.transactions', $cdAccountId)
            ->with('success', 'Transaction updated successfully!');
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy($cdAccountId, $id)
    {
        $cdAccount = CdMaster::findOrFail($cdAccountId);
        $transaction = CdAccTransaction::where('cd_ac_id', $cdAccountId)->findOrFail($id);

        // Store transaction values before deletion for balance calculation
        $transactionType = $transaction->credit_type;
        $transactionAmount = $transaction->transaction_amount;

        // Delete document if exists
        if ($transaction->document && file_exists(public_path($transaction->document))) {
            unlink(public_path($transaction->document));
        }

        $transaction->delete();

        // Update CD account current balance incrementally (reverse the transaction effect)
        if ($transactionType === 'CREDIT') {
            $cdAccount->current_balance = ($cdAccount->current_balance ?? 0) - $transactionAmount;
        } else { // DEBIT
            $cdAccount->current_balance = ($cdAccount->current_balance ?? 0) + $transactionAmount;
        }
        $cdAccount->save();

        return redirect()->route('admin.cd_account.transactions', $cdAccountId)
            ->with('success', 'Transaction deleted successfully!');
    }

    /**
     * Update CD account current balance based on transactions
     */
    private function updateCdAccountBalance($cdAccount)
    {
        $totalCredits = CdAccTransaction::where('cd_ac_id', $cdAccount->id)
            ->where('credit_type', 'CREDIT')
            ->sum('transaction_amount');

        $totalDebits = CdAccTransaction::where('cd_ac_id', $cdAccount->id)
            ->where('credit_type', 'DEBIT')
            ->sum('transaction_amount');

        $cdAccount->current_balance = $totalCredits - $totalDebits;
        $cdAccount->save();
    }
}

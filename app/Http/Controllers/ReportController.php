<?php
namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Lead;
use App\Models\User;

class ReportController extends Controller
{
    public function summary()
    {
        $userId = auth()->guard('api')->check()
        ? auth()->guard('api')->user()->role === 'COUNSELOR' ? auth()->guard('api')->user()->id
        : null : null;

        $leads = Lead::query()
            ->when($userId, fn($q) => $q->whereHas('maintainers', fn($query) => $query->where('user_id', $userId)))
            ->count();

        $progressLeads = Lead::query()
            ->when($userId, fn($q) => $q->whereHas('maintainers', fn($query) => $query->where('user_id', $userId)->where('status', 'IN_PROGRESS')))
            ->when(! $userId, fn($q) => $q->whereHas('maintainers', fn($query) => $query->where('status', 'IN_PROGRESS')))
            ->count();

        $badTimingLeads = Lead::query()
            ->when($userId, fn($q) => $q->whereHas('maintainers', fn($query) => $query->where('user_id', $userId)->where('status', 'BAD_TIMING')))
            ->when(! $userId, fn($q) => $q->whereHas('maintainers', fn($query) => $query->where('status', 'BAD_TIMING')))
            ->count();

        $notInterestedLeads = Lead::query()
            ->when($userId, fn($q) => $q->whereHas('maintainers', fn($query) => $query->where('user_id', $userId)->where('status', 'NOT_INTERESTED')))
            ->when(! $userId, fn($q) => $q->whereHas('maintainers', fn($query) => $query->where('status', 'NOT_INTERESTED')))
            ->count();

        $notQualifiedLeads = Lead::query()
            ->when($userId, fn($q) => $q->whereHas('maintainers', fn($query) => $query->where('user_id', $userId)->where('status', 'NOT_QUALIFIED')))
            ->when(! $userId, fn($q) => $q->whereHas('maintainers', fn($query) => $query->where('status', 'NOT_QUALIFIED')))
            ->count();

        $applications = Application::query()
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->count();

        $inProgressApplications = Application::query()
            ->when($userId, fn($q) => $q->where('user_id', $userId)->where('status', 'IN_PROGRESS'))
            ->count();

        $approvedApplications = Application::query()
            ->when($userId, fn($q) => $q->where('user_id', $userId)->where('status', 'APPROVED'))
            ->count();

        $rejectedApplications = Application::query()
            ->when($userId, fn($q) => $q->where('user_id', $userId)->where('status', 'REJECTED'))
            ->count();

        $counselors = User::query()
            ->when($userId, fn($q) => $q->where('id', $userId))
            ->where('role', 'COUNSELOR')
            ->count();

        return response()->json([
            'leads'                    => $leads,
            'counselors'               => $counselors,
            'applications'             => $applications,
            'progress_leads'           => $progressLeads,
            'bad_timing_leads'         => $badTimingLeads,
            'not_interested_leads'     => $notInterestedLeads,
            'not_qualified_leads'      => $notQualifiedLeads,
            'in_progress_applications' => $inProgressApplications,
            'approved_applications'    => $approvedApplications,
            'rejected_applications'    => $rejectedApplications,
        ]);
    }
}

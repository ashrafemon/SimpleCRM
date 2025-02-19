<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Lead;
use App\Models\User;
use Exception;

class ReportController extends Controller
{
    public function summary()
    {
        try {
            $userId = auth()->guard('api')->check() && auth()->guard('api')->user()->role === 'COUNSELOR'
            ? auth()->guard('api')->user()->id
            : null;

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
        } catch (Exception $e) {
            return serverError($e);
        }
    }

    public function counselorStats()
    {
        try {
            if (auth()->guard('api')->user()->role !== 'ADMIN') {
                return messageResponse('Sorry, you are not authorized to access this resource.', 403);
            }

            $stats = User::query()
                ->where('role', 'COUNSELOR')
                ->withCount(['leads as total_leads'])
                ->withCount([
                    'leads as completed_leads' => fn($query) => $query->whereHas('applications', fn($q) => $q->where('status', 'APPROVED')),
                ])
                ->get()
                ->map(fn($counselor) => [
                    'id'              => $counselor->id,
                    'name'            => $counselor->name,
                    'total_leads'     => $counselor->total_leads,
                    'completed_leads' => $counselor->completed_leads,
                    'conversion_rate' => $counselor->total_leads > 0
                    ? round(($counselor->completed_leads / $counselor->total_leads) * 100, 2)
                    : 0,
                ])
                ->sortByDesc('conversion_rate')
                ->values();

            return entityResponse([
                'stats'          => $stats,
                'top_performers' => $stats->take(5),
            ]);
        } catch (Exception $e) {
            return serverError($e);
        }
    }
}

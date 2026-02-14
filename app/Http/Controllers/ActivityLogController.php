<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    private const SUBJECT_TYPE_MAP = [
        'contracts' => 'Vertrag',
        'cars' => 'Auto',
        'contacts' => 'Kontakt',
        'App\Models\Payment' => 'Zahlung',
        'App\Models\Document' => 'Dokument',
    ];

    private const SUBJECT_ROUTE_MAP = [
        'contracts' => 'contracts.show',
        'cars' => 'cars.show',
        'contacts' => 'contacts.show',
    ];

    public function index(Request $request)
    {
        $query = Activity::with('causer')
            ->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('subject_type', 'like', "%{$search}%");
            });
        }

        return Inertia::render('ActivityLog/Index', [
            'filters' => $request->all('search'),
            'sort' => [
                'by' => 'created_at',
                'direction' => 'desc',
            ],
            'activities' => $query
                ->paginate(50)
                ->withQueryString()
                ->through(fn (Activity $activity) => [
                    'id' => $activity->id,
                    'description' => $this->translateDescription($activity->description),
                    'subject_type' => self::SUBJECT_TYPE_MAP[$activity->subject_type] ?? class_basename($activity->subject_type ?? ''),
                    'subject_id' => $activity->subject_id,
                    'causer_name' => $activity->causer?->name ?? '—',
                    'properties' => $this->formatProperties($activity),
                    'created_at' => Carbon::parse($activity->created_at)->format('d.m.Y H:i'),
                    'link' => $this->getSubjectLink($activity),
                ]),
        ]);
    }

    private function translateDescription(string $description): string
    {
        return match ($description) {
            'created' => 'Erstellt',
            'updated' => 'Bearbeitet',
            'deleted' => 'Gelöscht',
            default => $description,
        };
    }

    private function getSubjectLink(Activity $activity): ?string
    {
        $routeName = self::SUBJECT_ROUTE_MAP[$activity->subject_type] ?? null;

        if (! $routeName || ! $activity->subject_id) {
            return null;
        }

        if (! $activity->subject) {
            return null;
        }

        return route($routeName, $activity->subject_id);
    }

    private function formatProperties(Activity $activity): ?array
    {
        $old = $activity->properties->get('old');
        $new = $activity->properties->get('attributes');

        if (! $old && ! $new) {
            return null;
        }

        return [
            'old' => $old,
            'new' => $new,
        ];
    }
}

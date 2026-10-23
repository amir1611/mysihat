@extends('layouts.patient.patient-layout')

@section('content')
<div class="health-dashboard"><section class="dashboard-hero compact-hero"><div><span class="eyebrow">PERSONAL HEALTH LIBRARY</span><h1>My care summaries</h1><p>Review the notes saved from your MySihat Bot conversations.</p></div><a class="primary-action" href="{{ route('patient.chatbot') }}">Start a chat <i class="fas fa-comment"></i></a></section>
<section class="panel records-panel">@forelse($records as $record)<article class="record-card"><div class="record-icon"><i class="fas fa-file-medical"></i></div><div><span class="eyebrow">{{ \Carbon\Carbon::parse($record->created_at)->format('d M Y') }}</span><div class="record-text">{!! nl2br(e($record->summary)) !!}</div></div></article>@empty<div class="empty-state"><i class="far fa-folder-open"></i><h3>No summaries yet</h3><p>After a chat, choose an appointment to save a helpful summary here.</p></div>@endforelse</section></div>
@endsection

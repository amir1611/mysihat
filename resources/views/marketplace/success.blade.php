@extends('layouts.patient.patient-layout')

@section('content')
<div class="health-dashboard marketplace"><section class="order-confirmation panel"><div class="confirmation-icon"><i class="fas fa-check"></i></div><span class="eyebrow">ORDER CONFIRMED</span><h1>Thank you for caring for your health.</h1><p>Your demo order <strong>{{ $order['number'] }}</strong> has been confirmed. No payment was taken.</p><div class="confirmation-total">Order total <strong>RM {{ number_format($order['total'], 2) }}</strong></div><a class="primary-action dark-action" href="{{ route('marketplace.index') }}">Back to marketplace <i class="fas fa-arrow-right"></i></a></section></div>
@endsection

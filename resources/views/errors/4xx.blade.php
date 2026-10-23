@php($status = $exception->getStatusCode())
@php($statusText = __(\Symfony\Component\HttpFoundation\Response::$statusTexts[$status] ?? 'Error'))

@extends('errors::minimal')

@section('title', $statusText)
@section('code', $status)
@section('message', $statusText)

@extends('layouts.app')
@section('header', 'My Attendance Logs')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-slate-900 rounded-2xl p-6 text-white flex justify-between items-center shadow-lg">
        <div>
            <h2 class="text-xl font-bold">Daily Check-in</h2>
            <p class="text-slate-400 text-sm">Shift starts at 09:00 AM</p>
        </div>
        <form action="{{ route('attendance.checkin') }}" method="POST">
            @csrf
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-400 px-6 py-3 rounded-xl font-bold transition-all">
                Clock In Now
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="font-bold text-gray-800">My Recent Attendance</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="p-4">Date</th>
                        <th class="p-4">Check In</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($myLogs as $log)
                        <tr>
                            <td class="p-4 text-sm">{{ $log->created_at->format('M d, Y') }}</td>
                            <td class="p-4 text-sm font-medium">{{ $log->check_in }}</td>
                            <td class="p-4">
                                @if($log->status === 'Late')
                                    <span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-1 rounded-full">LATE</span>
                                @else
                                    <span class="bg-emerald-100 text-emerald-600 text-[10px] font-bold px-2 py-1 rounded-full">ON TIME</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-gray-400 text-sm">No attendance records found yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
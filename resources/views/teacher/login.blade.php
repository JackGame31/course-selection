@extends('layouts.main')

@section('content')
    <div class="flex flex-col items-center justify-center px-3">
        <div class="w-full bg-white border border-gray-300 rounded-lg shadow-lg sm:max-w-md xl:p-0">
            <div class="p-6 space-y-6 sm:p-8">
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 12v.01M12 16h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Teacher Panel Login</h1>
                </div>
                <p class="text-sm text-gray-500">Please authenticate to access the teacher dashboard.</p>

                <form class="space-y-5" method="POST" action="{{ route('teacher.login') }}">
                    @csrf
                    <div>
                        <label for="email" class="block mb-1 text-sm font-medium text-gray-900">Teacher Email</label>
                        <input type="email" id="email" name="email" placeholder="teacher@company.com" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="password" class="block mb-1 text-sm font-medium text-gray-900">Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-gray-600">
                            <input type="checkbox" name="remember" class="w-4 h-4 mr-2 border-gray-300 rounded">
                            Remember me
                        </label>
                        <a href="#" class="text-sm font-medium text-blue-600 hover:underline">Forgot password?</a>
                    </div>

                    <button type="submit"
                        class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-semibold rounded-lg text-sm px-5 py-2.5 text-center">
                        Sign in as teacher
                    </button>

                    <ul class="text-sm font-light text-gray-600 list-disc list-inside">
                        <li>
                            Don’t have an account yet? <a href="{{ route('teacher.register') }}"
                                class="font-medium text-blue-600 hover:underline">Sign up</a>
                        </li>
                        <li>
                            Are you a regular student? <a href="{{ route('login') }}"
                                class="font-medium text-blue-600 hover:underline">Switch to student login</a>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
@endsection

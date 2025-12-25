@extends('layouts.main')

@section('content')
    <div class="flex flex-col items-center justify-center max-w-md">
        <div class="w-full bg-white rounded-lg shadow-md">
            <div class="p-6 space-y-6">
                <h1 class="text-xl font-semibold text-gray-900 text-center">Sign in to your account</h1>
                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    <!-- Email input -->
                    <div>
                        <label for="email" class="block text-sm text-gray-700">Your email</label>
                        <input type="email" name="email" id="email" placeholder="name@email.com"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>

                    <!-- Password input -->
                    <div>
                        <label for="password" class="block text-sm text-gray-700">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>

                    <!-- Remember me and forgot password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" type="checkbox" class="w-4 h-4 border-gray-300 rounded bg-gray-50">
                            <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                        </div>
                        <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                    </div>

                    <!-- Submit button -->
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                        Sign in
                    </button>

                    <!-- Links for signup and admin login -->
                    <ul class="text-sm text-center text-gray-600 space-y-1">
                        <li>Don’t have an account?
                            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Sign up</a>
                        </li>
                        <li>Are you a teacher?
                            <a href="{{ route('teacher.login') }}" class="text-blue-600 hover:underline">Switch to
                                teacher login</a>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
@endsection

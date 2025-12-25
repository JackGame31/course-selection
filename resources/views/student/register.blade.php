@extends('layouts.main')

@section('content')
    <div class="flex flex-col items-center justify-center">
        <div class="w-full bg-white rounded-lg shadow-md">
            <div class="p-6 space-y-6">
                <h1 class="text-xl font-semibold text-gray-900 text-center">Create an account</h1>
                <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Username Field -->
                    <div>
                        <label for="name" class="block text-sm text-gray-700">Your name</label>
                        <input type="text" name="name" id="name" placeholder="John Doe"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm text-gray-700">Your email</label>
                        <input type="email" name="email" id="email" placeholder="name@email.com"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm text-gray-700">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        @error('password')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="confirm-password" class="block text-sm text-gray-700">Confirm password</label>
                        <input type="password" name="confirm-password" id="confirm-password" placeholder="••••••••"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        @error('confirm-password')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Terms and Conditions Checkbox -->
                    <div class="flex items-start">
                        <input id="terms" name="terms" type="checkbox"
                            class="w-4 h-4 border-gray-300 rounded bg-gray-50" required
                            @if (old('terms')) checked @endif>
                        <label for="terms" class="ml-2 text-sm text-gray-600">
                            I accept the
                            <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a>
                        </label>
                        @error('terms')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                        Create an account
                    </button>

                    <!-- Already have an account link -->
                    <ul class="text-sm text-center text-gray-600">
                        <li>Already have an account?
                            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login here</a>
                        </li>
                        <li>Are you a teacher?
                            <a href="{{ route('teacher.register') }}" class="text-blue-600 hover:underline">Switch to
                                teacher register</a>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
@endsection

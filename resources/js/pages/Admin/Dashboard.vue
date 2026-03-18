<script setup lang="ts">
import { router } from '@inertiajs/vue3';

// Logout
const logout = () => {
    router.post('/logout');
};

// Go to User Dashboard
const goUserDashboard = () => {
    router.get('/dashboard'); // matches your existing route
};
</script>

<template>
  <div class="p-6">
    
    <!-- Top Actions -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-red-600">Admin Dashboard</h1>

      <div class="flex gap-3">
        <!-- User Dashboard Button -->
        <button 
          @click="goUserDashboard"
          class="px-4 py-2 bg-blue-500 text-white rounded"
        >
          User Dashboard
        </button>

        <!-- Logout Button -->
        <button 
          @click="logout"
          class="px-4 py-2 bg-red-500 text-white rounded"
        >
          Logout
        </button>
      </div>
    </div>

    <p>Welcome, {{ $page.props.auth.user.name }} </p>

    <!-- Admin Stats -->
    <div class="grid grid-cols-3 gap-6 mt-6">
      <div class="p-4 bg-white shadow rounded">
        <h2 class="font-semibold">Total Users</h2>
        <p>---</p>
      </div>

      <div class="p-4 bg-white shadow rounded">
        <h2 class="font-semibold">Total Bookings</h2>
        <p>---</p>
      </div>

      <div class="p-4 bg-white shadow rounded">
        <h2 class="font-semibold">Revenue</h2>
        <p>---</p>
      </div>
    </div>

  </div>
</template>
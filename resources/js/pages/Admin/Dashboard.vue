<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import SidebarLayout from '@/components/AppSidebar.vue';

defineOptions({
  layout: SidebarLayout,
});

const logout = () => router.post('/logout');
const goUserDashboard = () => router.get('/dashboard');
</script>

<template>
  <div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
        <p class="text-gray-500">Welcome, {{ $page.props.auth.user.name }}</p>
      </div>

      <div class="flex gap-3">
        <button 
          @click="goUserDashboard"
          class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg"
        >
          User Dashboard
        </button>

        <button 
          @click="logout"
          class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg"
        >
          Logout
        </button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-gray-500 text-sm">Total Users</h2>
        <p class="text-2xl font-bold mt-2">120</p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-gray-500 text-sm">Total Bookings</h2>
        <p class="text-2xl font-bold mt-2">75</p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-gray-500 text-sm">Revenue</h2>
        <p class="text-2xl font-bold mt-2">Rs. 250,000</p>
      </div>

    </div>
  </div>
</template>
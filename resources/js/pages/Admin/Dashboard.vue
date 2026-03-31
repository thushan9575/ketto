<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3'
import { computed, ref, onMounted } from 'vue'
import SidebarLayout from '@/components/AppSidebar.vue'
import axios from 'axios'
import Chart from 'chart.js/auto'


defineOptions({
  layout: SidebarLayout,
})


const page = usePage()
const user = computed(() => page.props.auth?.user)


const totalUsers = ref(0)
const totalBookings = ref(0)
const revenue = ref(0)

const loading = ref(true)

let chart: any = null

const logout = () => router.post('/logout')
const goUserDashboard = () => router.visit('/dashboard')

async function fetchStats() {
  try {
    const res = await axios.get('/api/admin/stats')

    totalUsers.value = res.data.totalUsers || 0
    totalBookings.value = res.data.totalBookings || 0
    revenue.value = res.data.revenue || 0

    

    setTimeout(() => {
      renderChart()
    }, 300)

  } catch (e) {
    console.error('Stats error:', e)

  

    setTimeout(() => {
      renderChart()
    }, 300)

  } finally {
    loading.value = false
  }
}

function renderChart() {
  const canvas = document.getElementById('revenueChart') as HTMLCanvasElement

  if (!canvas) {
    console.log(' Canvas not found')
    return
  }
}

onMounted(fetchStats)
</script>

<template>
  <div class="w-full">

    <div class="bg-black text-white p-4 rounded-xl mb-8 shadow w-full">
      <div class="flex justify-between items-center">

        <div class="flex items-center gap-3">
          <img src="/images/logo.png" class="w-28" />
          <div>
            <h1 class="text-xl font-bold">Admin Panel</h1>
            <p class="text-gray-300 text-xs">
              Welcome, {{ user?.name }}
            </p>
          </div>
        </div>

        <div class="flex gap-3">
          <button
            @click="goUserDashboard"
            class="px-4 py-2 border border-white rounded hover:bg-white hover:text-black"
          >
            User Dashboard
          </button>

          <button
            @click="logout"
            class="px-4 py-2 border border-red-400 text-red-400 rounded hover:bg-red-500 hover:text-white"
          >
            Logout
          </button>
        </div>

      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-gray-500 text-sm">Total Users</h2>
        <p class="text-3xl font-bold mt-2">
          {{ loading ? '...' : totalUsers }}
        </p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-gray-500 text-sm">Total Bookings</h2>
        <p class="text-3xl font-bold mt-2">
          {{ loading ? '...' : totalBookings }}
        </p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-gray-500 text-sm">Revenue</h2>
        <p class="text-3xl font-bold mt-2 text-green-600">
          Rs. {{ loading ? '...' : revenue }}
        </p>
      </div>

    </div>

    

    <div class="mt-10 bg-white p-6 rounded-xl shadow">
      <h2 class="text-xl font-semibold mb-6 text-center">
        Quick Actions
      </h2>

      <div class="flex flex-wrap justify-center gap-4">

        <button
          @click="router.visit('/admin/rooms')"
          class="px-6 py-2 bg-black text-white rounded hover:bg-gray-800"
        >
          Manage Rooms
        </button>

        <button
          @click="router.visit('/admin/room-types')"
          class="px-6 py-2 bg-black text-white rounded hover:bg-gray-800"
        >
          Room Types
        </button>

        <button
          @click="router.visit('/admin/bookings')"
          class="px-6 py-2 bg-black text-white rounded hover:bg-gray-800"
        >
          Bookings
        </button>

      </div>
    </div>

  </div>
</template>
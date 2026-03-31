<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import SidebarLayout from '@/components/AppSidebar.vue'
import { usePage, router } from '@inertiajs/vue3'

defineOptions({
  layout: SidebarLayout,
})

const page = usePage()
const user = computed(() => page.props.auth?.user)

const bookings = ref<any[]>([])
const loading = ref(false)

async function fetchBookings() {
  try {
    loading.value = true
    const res = await axios.get('/api/bookings')
    bookings.value = res.data
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
}

async function deleteBooking(id: number) {
  if (!confirm('Delete this booking?')) return

  await axios.delete(`/api/bookings/${id}`)
  fetchBookings()
}

onMounted(fetchBookings)
</script>

<template>
  <div class="w-full">

    <div class="bg-black text-white p-4 rounded-xl mb-8 shadow">
      <div class="flex justify-between items-center">

        <div class="flex items-center gap-3">
          <img src="/images/logo.png" class="w-28" />
          <div>
            <h1 class="text-xl font-bold">Bookings</h1>
            <p class="text-gray-300 text-xs">
              Manage all hotel bookings
            </p>
          </div>
        </div>

        <button
          @click="router.visit('/admin/dashboard')"
          class="px-4 py-2 border border-white rounded hover:bg-white hover:text-black"
        >
          Dashboard
        </button>

      </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow">

      <h2 class="text-xl font-semibold mb-4 text-gray-700">
        Bookings List
      </h2>

      <div v-if="loading" class="text-center text-gray-500 py-6">
        Loading bookings...
      </div>

      <div v-else class="overflow-x-auto">

        <table class="min-w-full text-sm">

          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="p-3">ID</th>
              <th class="p-3">Name</th>
              <th class="p-3">Email</th>
              <th class="p-3">Room</th>
              <th class="p-3">Arrival</th>
              <th class="p-3">Departure</th>
              <th class="p-3 text-center">Action</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="b in bookings"
              :key="b.id"
              class="border-b hover:bg-gray-50"
            >
              <td class="p-3">{{ b.id }}</td>
              <td class="p-3 font-medium">{{ b.name }}</td>
              <td class="p-3">{{ b.email }}</td>
              <td class="p-3">{{ b.room?.room_title || 'N/A' }}</td>
              <td class="p-3">{{ b.arrival }}</td>
              <td class="p-3">{{ b.departure }}</td>

              <td class="p-3 flex justify-center">
                <button
                  @click="deleteBooking(b.id)"
                  class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                >
                  Delete
                </button>
              </td>
            </tr>

            <tr v-if="bookings.length === 0">
              <td colspan="7" class="text-center p-6 text-gray-400">
                No bookings found
              </td>
            </tr>
          </tbody>

        </table>

      </div>

    </div>

  </div>
</template>
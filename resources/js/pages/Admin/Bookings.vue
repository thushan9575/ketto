<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const bookings = ref<any[]>([])
const loading = ref(false)

// Load bookings
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

// Delete booking
async function deleteBooking(id: number) {
  if (!confirm('Are you sure?')) return

  try {
    await axios.delete(`/api/bookings/${id}`)
    fetchBookings()
  } catch (err) {
    console.error(err)
  }
}

onMounted(() => {
  fetchBookings()
})
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Booking Dashboard</h1>

    <div v-if="loading">Loading...</div>

    <div v-else class="bg-white shadow rounded p-4">
      <table class="min-w-full border text-sm">
        <thead>
          <tr class="bg-gray-200">
            <th class="p-2">ID</th>
            <th class="p-2">Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">Room</th>
            <th class="p-2">Arrival</th>
            <th class="p-2">Departure</th>
            <th class="p-2">Action</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="b in bookings" :key="b.id" class="border-b">
            <td class="p-2">{{ b.id }}</td>
            <td class="p-2">{{ b.name }}</td>
            <td class="p-2">{{ b.email }}</td>
            <td class="p-2">{{ b.room?.room_title }}</td>
            <td class="p-2">{{ b.arrival }}</td>
            <td class="p-2">{{ b.departure }}</td>

            <td class="p-2">
              <button
                @click="deleteBooking(b.id)"
                class="bg-red-500 text-white px-3 py-1 rounded"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="bookings.length === 0" class="text-center p-4">
        No bookings found
      </div>
    </div>
  </div>
</template>
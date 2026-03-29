<script setup lang="ts">
import { ref, onMounted, computed, watch, nextTick } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import axios from 'axios'
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.css'

// ✅ Auth user
const page = usePage()
const authUser = computed(() => page.props.auth?.user)

// ✅ Form
const form = ref({
  name: authUser.value?.name || '',
  email: authUser.value?.email || '',
  arrival: '',
  departure: '',
  room_id: ''
})

// ✅ Data
const rooms = ref<any[]>([])
const bookedDates = ref<any[]>([])
const loading = ref(false)

// ✅ Refs for calendar inputs
const arrivalInput = ref(null)
const departureInput = ref(null)

// ✅ Selected room
const selectedRoom = computed(() => {
  return rooms.value.find(r => r.id == form.value.room_id)
})

// ✅ Fetch rooms
async function fetchRooms() {
  const res = await axios.get('/api/rooms')
  rooms.value = res.data
}

// ✅ Fetch booked dates
async function fetchBookedDates() {
  if (!form.value.room_id) return

  const res = await axios.get(`/api/rooms/${form.value.room_id}/booked-dates`)
  bookedDates.value = res.data
}

// ✅ Convert booked ranges for flatpickr
function getDisabledRanges() {
  return bookedDates.value.map((b: any) => ({
    from: b.arrival,
    to: b.departure
  }))
}

// ✅ Initialize calendar
async function initCalendar() {
  await nextTick()

  flatpickr(arrivalInput.value, {
    dateFormat: 'Y-m-d',
    disable: getDisabledRanges(),
    onChange: (dates) => {
      form.value.arrival = dates[0]?.toISOString().split('T')[0]
    }
  })

  flatpickr(departureInput.value, {
    dateFormat: 'Y-m-d',
    disable: getDisabledRanges(),
    onChange: (dates) => {
      form.value.departure = dates[0]?.toISOString().split('T')[0]
    }
  })
}

// ✅ Watch room change → reload disabled dates
watch(() => form.value.room_id, async () => {
  await fetchBookedDates()
  initCalendar()
})

// ✅ Submit booking
async function submitBooking() {
  if (!authUser.value) {
    alert('❌ Please login first')
    router.visit('/login')
    return
  }

  try {
    loading.value = true

    await axios.post('/api/bookings', form.value)

    alert('✅ Booking successful!')

    router.visit('/') // redirect home

  } catch (e: any) {
    alert('❌ ' + (e.response?.data?.message || 'Booking failed'))
  } finally {
    loading.value = false
  }
}

// ✅ Init
onMounted(async () => {
  await fetchRooms()

  const params = new URLSearchParams(window.location.search)
  const roomId = params.get('room_id')

  if (roomId) {
    form.value.room_id = roomId
    await fetchBookedDates()
    initCalendar()
  }
})
</script>

<template>
  <div class="max-w-5xl mx-auto p-6 mt-10 grid md:grid-cols-2 gap-6">

    <!-- LEFT: ROOM DETAILS -->
    <div v-if="selectedRoom" class="bg-white shadow rounded overflow-hidden">
      <img
        v-if="selectedRoom.image"
        :src="`/storage/${selectedRoom.image}`"
        class="w-full h-64 object-cover"
      />

      <div class="p-4">
        <h2 class="text-xl font-bold">{{ selectedRoom.room_title }}</h2>

        <p class="text-gray-500 mt-2">
          {{ selectedRoom.description }}
        </p>

        <p class="mt-3 font-bold text-blue-600 text-lg">
          Rs. {{ selectedRoom.price }}
        </p>

        <p class="mt-2 text-sm">
          Wifi:
          <span class="font-semibold">
            {{ selectedRoom.wifi ? 'Available' : 'Not Available' }}
          </span>
        </p>
      </div>
    </div>

    <!-- RIGHT: BOOKING FORM -->
    <div class="bg-white shadow rounded p-6">

      <h2 class="text-2xl mb-4">Book Room</h2>

      <!-- Logged user -->
      <div v-if="authUser">

        <input
          v-model="form.name"
          placeholder="Name"
          class="border p-2 w-full mb-2 rounded"
        />

        <input
          v-model="form.email"
          placeholder="Email"
          class="border p-2 w-full mb-2 rounded"
        />

        <!-- ✅ Flatpickr Arrival -->
        <input
          ref="arrivalInput"
          placeholder="Arrival Date"
          class="border p-2 w-full mb-2 rounded"
        />

        <!-- ✅ Flatpickr Departure -->
        <input
          ref="departureInput"
          placeholder="Departure Date"
          class="border p-2 w-full mb-2 rounded"
        />

        <!-- Room -->
        <select
          v-model="form.room_id"
          class="border p-2 w-full mb-3 rounded"
        >
          <option value="">Select Room</option>
          <option v-for="room in rooms" :key="room.id" :value="room.id">
            {{ room.room_title }} (Rs. {{ room.price }})
          </option>
        </select>

        <button
          @click="submitBooking"
          :disabled="loading"
          class="bg-black text-white w-full py-2 rounded"
        >
          {{ loading ? 'Booking...' : 'Book Now' }}
        </button>

      </div>

      <!-- Guest -->
      <div v-else class="text-center text-red-500">
        Please login to book a room
      </div>

    </div>

  </div>
</template>
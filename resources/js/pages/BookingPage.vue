<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import axios from 'axios'
import flatpickr from "flatpickr"
import "flatpickr/dist/flatpickr.css"

const page = usePage()
const authUser = computed(() => page.props.auth?.user)

const form = ref({
  name: authUser.value?.name || '',
  email: authUser.value?.email || '',
  arrival: '',
  departure: '',
  room_id: ''
})

const rooms = ref<any[]>([])
const bookedDates = ref<any[]>([])
const loading = ref(false)

const selectedRoom = computed(() => {
  return rooms.value.find(r => r.id == form.value.room_id)
})

async function fetchRooms() {
  const res = await axios.get('/api/rooms')
  rooms.value = res.data
}

async function fetchBookedDates() {
  if (!form.value.room_id) return
  const res = await axios.get(`/api/rooms/${form.value.room_id}/booked-dates`)
  bookedDates.value = res.data

  initFlatpickr() 
}

let fpArrival: any = null
let fpDeparture: any = null
function initFlatpickr() {
  const disabledDates: string[] = []

  bookedDates.value.forEach(b => {
    let current = new Date(b.arrival)
    const end = new Date(b.departure)
    while (current < end) { 
      disabledDates.push(current.toISOString().split('T')[0])
      current.setDate(current.getDate() + 1)
    }
  })

  if (fpArrival) fpArrival.destroy()
  if (fpDeparture) fpDeparture.destroy()

  fpArrival = flatpickr("#arrival", {
    dateFormat: "Y-m-d",
    disable: disabledDates,
    minDate: "today",
    onChange: (selectedDates) => {
      form.value.arrival = selectedDates[0]?.toISOString().split('T')[0] || ''
    }
  })

  fpDeparture = flatpickr("#departure", {
    dateFormat: "Y-m-d",
    disable: disabledDates,
    minDate: form.value.arrival || "today",
    onChange: (selectedDates) => {
      form.value.departure = selectedDates[0]?.toISOString().split('T')[0] || ''
    }
  })
}

watch(() => form.value.room_id, fetchBookedDates)

async function submitBooking() {
  if (!authUser.value) {
    alert(' Login required')
    router.visit('/login')
    return
  }

  if (!form.value.room_id || !form.value.arrival || !form.value.departure) {
    alert(' Please select a room and dates')
    return
  }

  try {
    loading.value = true
    await axios.post('/api/bookings', form.value)
    alert(' Booking successful!')
    router.visit('/')
  } catch (e: any) {
    alert(' Booking failed: ' + (e.response?.data?.message || 'Error'))
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await fetchRooms()

  const params = new URLSearchParams(window.location.search)
  const roomId = params.get('room_id')
  if (roomId) form.value.room_id = roomId
})
</script>

<template>
  <div class="max-w-5xl mx-auto p-6 mt-10 grid md:grid-cols-2 gap-6">
    <div v-if="selectedRoom" class="bg-white shadow rounded overflow-hidden">
      <img v-if="selectedRoom.image" :src="`/storage/${selectedRoom.image}`" class="w-full h-64 object-cover"/>
      <div class="p-4">
        <h2 class="text-xl font-bold">{{ selectedRoom.room_title }}</h2>
        <p class="text-gray-500 mt-2">{{ selectedRoom.description }}</p>
        <p class="mt-3 font-bold text-blue-600">Rs. {{ selectedRoom.price }}</p>
        <p class="mt-2 text-sm">Wifi: {{ selectedRoom.wifi ? 'Available' : 'No' }}</p>
      </div>
    </div>

    <div class="bg-white shadow rounded p-6">
      <h2 class="text-2xl mb-4">Book Room</h2>

      <div v-if="authUser">
        <input v-model="form.name" placeholder="Name" class="border p-2 w-full mb-2 rounded"/>
        <input v-model="form.email" placeholder="Email" class="border p-2 w-full mb-2 rounded"/>
        
        <select v-model="form.room_id" class="border p-2 w-full mb-3 rounded">
          <option value="">Select Room</option>
          <option v-for="room in rooms" :key="room.id" :value="room.id">
            {{ room.room_title }} (Rs. {{ room.price }})
          </option>
        </select>

        <input id="arrival" placeholder="Arrival" class="border p-2 w-full mb-2 rounded"/>
        <input id="departure" placeholder="Departure" class="border p-2 w-full mb-3 rounded"/>

        <button @click="submitBooking" :disabled="loading" class="bg-black text-white w-full py-2 rounded hover:bg-gray-800">
          {{ loading ? 'Booking...' : 'Book Now' }}
        </button>
      </div>

      <div v-else class="text-center text-red-500">
        Please login to book
      </div>
    </div>
  </div>
</template>
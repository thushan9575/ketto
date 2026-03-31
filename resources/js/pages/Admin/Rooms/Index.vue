<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3'
import { computed, ref, onMounted } from 'vue'
import SidebarLayout from '@/components/AppSidebar.vue'
import AddRoomForm from './AddRoomform.vue'
import axios from 'axios'

defineOptions({
  layout: SidebarLayout,
})

// ✅ Auth user
const page = usePage()
const user = computed(() => page.props.auth?.user)

// ✅ Actions
const logout = () => router.post('/logout')
const goUserDashboard = () => router.visit('/dashboard')

// ✅ Rooms
interface Room {
  id: string
  room_title: string
  image: string
  room_type_id: string
  description: string
  price: number
  wifi: boolean
}

interface RoomType {
  id: string
  name: string
}

const rooms = ref<Room[]>([])
const roomTypes = ref<RoomType[]>([])
const selectedRoom = ref<Room | null>(null)

// ✅ Stats
const totalRooms = ref(0)

const loading = ref(true)

// ✅ Fetch rooms
async function fetchRooms() {
  const res = await axios.get('/api/rooms')
  rooms.value = res.data
  totalRooms.value = rooms.value.length
}

// ✅ Fetch room types
async function fetchRoomTypes() {
  const res = await axios.get('/api/room-types')
  roomTypes.value = res.data
}

// ✅ Fetch stats (bookings & revenue) from backend
async function fetchStats() {
  try {
    loading.value = true
    const res = await axios.get('/api/admin/stats')
 
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
}

// ✅ Helper
function getRoomTypeName(id: string) {
  const type = roomTypes.value.find(t => t.id === id)
  return type ? type.name : 'N/A'
}

// ✅ Room actions
function editRoom(room: Room) {
  selectedRoom.value = { ...room }
}

async function deleteRoom(id: string) {
  if (!confirm('Are you sure to delete this room?')) return
  await axios.delete(`/api/rooms/${id}`)
  fetchRooms()
  fetchStats()
}

function onSaved() {
  selectedRoom.value = null
  fetchRooms()
  fetchStats()
}

// ✅ Init
onMounted(() => {
  fetchRooms()
  fetchRoomTypes()
  fetchStats()
})
</script>

<template>
  <div class="w-full">

    <!-- HEADER -->
    <div class="bg-black text-white p-4 rounded-xl mb-8 shadow w-full">
      <div class="flex justify-between items-center">
        <div class="flex items-center gap-3">
          <img src="/images/logo.png" class="w-28" />
          <div>
            <h1 class="text-xl font-bold">Admin Panel</h1>
            <p class="text-gray-300 text-xs">Welcome, {{ user?.name }}</p>
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

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-xl shadow text-center">
        <h2 class="text-gray-500 text-sm">Total Rooms</h2>
        <p class="text-3xl font-bold mt-2">
          {{ loading ? '...' : totalRooms }}
        </p>
      </div>

      

     
    </div>

    

    <!-- MAIN CONTENT -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">

      <!-- FORM -->
      <div class="md:col-span-1">
        <AddRoomForm :initial="selectedRoom" @saved="onSaved" />
      </div>

      <!-- ROOM LIST TABLE -->
      <div class="md:col-span-2 bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-4 text-gray-700 text-center">Rooms List</h2>

        <table class="min-w-full border text-sm">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-2">ID</th>
              <th class="p-2">Image</th>
              <th class="p-2">Title</th>
              <th class="p-2">Type</th>
              <th class="p-2">Price</th>
              <th class="p-2">Wifi</th>
              <th class="p-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="room in rooms" :key="room.id" class="border-b hover:bg-gray-50">
              <td class="p-2 text-center">{{ room.id }}</td>
              <td class="p-2 text-center">
                <img
                  v-if="room.image"
                  :src="`/storage/${room.image}`"
                  class="h-14 w-14 object-cover rounded mx-auto"
                />
              </td>
              <td class="p-2">{{ room.room_title }}</td>
              <td class="p-2">{{ getRoomTypeName(room.room_type_id) }}</td>
              <td class="p-2">Rs. {{ room.price }}</td>
              <td class="p-2 text-center">{{ room.wifi ? 'Yes' : 'No' }}</td>
              <td class="p-2 flex gap-2 justify-center">
                <button
                  @click="editRoom(room)"
                  class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                  Edit
                </button>
                <button
                  @click="deleteRoom(room.id)"
                  class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</template>
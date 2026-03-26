<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Rooms Management</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      <!-- Form -->
      <div class="md:col-span-1">
        <AddRoomForm :initial="selectedRoom" @saved="onSaved" />
      </div>

      <!-- Table -->
      <div class="md:col-span-2 bg-white p-4 rounded shadow">
        <h2 class="text-lg font-semibold mb-4">Rooms List</h2>

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
            <tr v-for="room in rooms" :key="room.id" class="border-b">
              <td class="p-2">{{ room.id }}</td>

              <td class="p-2">
                <img v-if="room.image" :src="`/storage/${room.image}`" class="h-14 w-14" />
              </td>

              <td class="p-2">{{ room.room_title }}</td>

              <!-- ✅ show room type name -->
              <td class="p-2">
                {{ getRoomTypeName(room.room_type_id) }}
              </td>

              <td class="p-2">{{ room.price }}</td>
              <td class="p-2">{{ room.wifi ? 'Yes' : 'No' }}</td>

              <td class="p-2 flex gap-2">
                <button @click="editRoom(room)">Edit</button>
                <button @click="deleteRoom(room.id)">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AddRoomForm from './AddRoomform.vue'

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

// fetch rooms
async function fetchRooms() {
  const res = await axios.get('/api/rooms')
  rooms.value = res.data
}

// fetch room types
async function fetchRoomTypes() {
  const res = await axios.get('/api/room-types')
  roomTypes.value = res.data
}

// map id → name
function getRoomTypeName(id: string) {
  const type = roomTypes.value.find(t => t.id === id)
  return type ? type.name : 'N/A'
}

function editRoom(room: Room) {
  selectedRoom.value = { ...room }
}

async function deleteRoom(id: string) {
  await axios.delete(`/api/rooms/${id}`)
  fetchRooms()
}

function onSaved() {
  selectedRoom.value = null
  fetchRooms()
}

onMounted(() => {
  fetchRooms()
  fetchRoomTypes()
})
</script>
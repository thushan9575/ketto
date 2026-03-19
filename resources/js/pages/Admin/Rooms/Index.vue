<template>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Rooms Management</h1>
  
      <!-- Grid layout: left form / right table -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  
        <!-- Left: Form -->
        <div class="md:col-span-1">
          <AddRoomForm :initial="selectedRoom" @saved="onSaved" />
        </div>
  
        <!-- Right: Table -->
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
              <tr v-for="room in rooms" :key="room.id" class="border-b hover:bg-gray-50">
                <td class="p-2">{{ room.id }}</td>
                <td class="p-2">
                  <img
                    v-if="room.image"
                    :src="`/storage/${room.image}`"
                    class="h-14 w-14 object-cover rounded"
                  />
                </td>
                <td class="p-2">{{ room.room_title }}</td>
                <td class="p-2">{{ room.room_type }}</td>
                <td class="p-2">{{ room.price }}</td>
                <td class="p-2">{{ room.wifi ? 'Yes' : 'No' }}</td>
  
                <td class="p-2 flex gap-2">
                  <button
                    class="p-2 rounded-lg bg-yellow-100 hover:bg-yellow-200 text-yellow-600"
                    @click="editRoom(room)"
                  >
                    <PencilIcon class="h-5 w-5" />
                  </button>
  
                  <button
                    class="p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-600"
                    @click="deleteRoom(room.id)"
                  >
                    <TrashIcon class="h-5 w-5" />
                  </button>
                </td>
              </tr>
  
              <tr v-if="rooms.length === 0">
                <td colspan="7" class="text-center p-4 text-gray-500">
                  No rooms found
                </td>
              </tr>
            </tbody>
          </table>
        </div>
  
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import SidebarLayout from '@/components/AppSidebar.vue'
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  import AddRoomForm from './AddRoomForm.vue'
  import { PencilIcon, TrashIcon } from '@heroicons/vue/24/solid'
  
  defineOptions({
    layout: SidebarLayout,
  })
  
  interface Room {
    id: string
    room_title: string
    image: string
    room_type: string
    description: string
    price: number
    wifi: boolean
  }
  
  const rooms = ref<Room[]>([])
  const selectedRoom = ref<Room | null>(null)
  
  // Fetch rooms
  async function fetchRooms() {
    try {
      const res = await axios.get('/api/rooms')
      rooms.value = res.data
    } catch (err) {
      console.error(err)
    }
  }
  
  // Edit room
  function editRoom(room: Room) {
    selectedRoom.value = { ...room }
  }
  
  // Delete room
  async function deleteRoom(id: string) {
    if (!confirm('Are you sure you want to delete this room?')) return
    try {
      await axios.delete(`/api/rooms/${id}`)
      fetchRooms()
    } catch (err) {
      console.error(err)
    }
  }
  
  // After save
  function onSaved() {
    selectedRoom.value = null
    fetchRooms()
  }
  
  onMounted(fetchRooms)
  </script>
<template>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Room Types Management</h1>
  
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  
        <!-- Left: Form -->
        <div class="md:col-span-1">
          <AddRoomTypeForm :initial="selected" @saved="onSaved" />
        </div>
  
        <!-- Right: Table -->
        <div class="md:col-span-2 bg-white p-4 rounded shadow">
          <h2 class="text-lg font-semibold mb-4">Room Types List</h2>
  
          <table class="min-w-full border text-sm">
            <thead>
              <tr class="bg-gray-200">
                <th class="p-2">ID</th>
                <th class="p-2">Name</th>
                <th class="p-2">Actions</th>
              </tr>
            </thead>
  
            <tbody>
              <tr
                v-for="item in roomTypes"
                :key="item.id"
                class="border-b hover:bg-gray-50"
              >
                <td class="p-2">{{ item.id }}</td>
                <td class="p-2">{{ item.name }}</td>
  
                <td class="p-2 flex gap-2">
                  <button
                    class="p-2 bg-yellow-100 text-yellow-600 rounded"
                    @click="edit(item)"
                  >
                    Edit
                  </button>
  
                  <button
                    class="p-2 bg-red-100 text-red-600 rounded"
                    @click="remove(item.id)"
                  >
                    Delete
                  </button>
                </td>
              </tr>
  
              <tr v-if="roomTypes.length === 0">
                <td colspan="3" class="text-center p-4 text-gray-500">
                  No room types found
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
  import AddRoomTypeForm from './AddRoomTypesForm.vue'
  
  defineOptions({
    layout: SidebarLayout,
  })
  
  interface RoomType {
    id: string
    name: string
  }
  
  const roomTypes = ref<RoomType[]>([])
  const selected = ref<RoomType | null>(null)
  
  // Fetch
  async function fetchData() {
    try {
      const res = await axios.get('/api/room-types')
      roomTypes.value = res.data
    } catch (err) {
      console.error(err)
    }
  }
  
  // Edit
  function edit(item: RoomType) {
    selected.value = { ...item }
  }
  
  // Delete
  async function remove(id: string) {
    if (!confirm('Delete this room type?')) return
    try {
      await axios.delete(`/api/room-types/${id}`)
      fetchData()
    } catch (err) {
      console.error(err)
    }
  }
  
  // After save
  function onSaved() {
    selected.value = null
    fetchData()
  }
  
  onMounted(fetchData)
  </script>
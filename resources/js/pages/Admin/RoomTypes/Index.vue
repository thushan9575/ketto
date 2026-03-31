<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import SidebarLayout from '@/components/AppSidebar.vue'
import { usePage, router } from '@inertiajs/vue3'
import AddRoomTypeForm from './AddRoomTypesForm.vue'

defineOptions({
  layout: SidebarLayout,
})

// Auth user
const page = usePage()
const user = computed(() => page.props.auth?.user)

interface RoomType {
  id: string
  name: string
}

const roomTypes = ref<RoomType[]>([])
const selected = ref<RoomType | null>(null)

// Fetch
async function fetchData() {
  const res = await axios.get('/api/room-types')
  roomTypes.value = res.data
}

// Edit
function edit(item: RoomType) {
  selected.value = { ...item }
}

// Delete
async function remove(id: string) {
  if (!confirm('Delete this room type?')) return
  await axios.delete(`/api/room-types/${id}`)
  fetchData()
}

// After save
function onSaved() {
  selected.value = null
  fetchData()
}

onMounted(fetchData)
</script>

<template>
  <div class="w-full">

    <!-- 🔥 HEADER -->
    <div class="bg-black text-white p-4 rounded-xl mb-8 shadow">
      <div class="flex justify-between items-center">

        <div class="flex items-center gap-3">
          <img src="/images/logo.png" class="w-28" />
          <div>
            <h1 class="text-xl font-bold">Room Types</h1>
            <p class="text-gray-300 text-xs">
              Manage your room categories
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

    <!-- 🔥 CONTENT -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      <!-- FORM -->
      <div>
        <AddRoomTypeForm :initial="selected" @saved="onSaved" />
      </div>

      <!-- LIST -->
      <div class="md:col-span-2 bg-white p-6 rounded-xl shadow">

        <h2 class="text-xl font-semibold mb-4 text-gray-700">
          Room Types List
        </h2>

        <table class="min-w-full text-sm">

          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="p-3">ID</th>
              <th class="p-3">Name</th>
              <th class="p-3 text-center">Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="item in roomTypes"
              :key="item.id"
              class="border-b hover:bg-gray-50"
            >
              <td class="p-3">{{ item.id }}</td>
              <td class="p-3 font-medium">{{ item.name }}</td>

              <td class="p-3 flex justify-center gap-2">
                <button
                  @click="edit(item)"
                  class="px-3 py-1 bg-black text-white rounded hover:bg-gray-800"
                >
                  Edit
                </button>

                <button
                  @click="remove(item.id)"
                  class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                >
                  Delete
                </button>
              </td>
            </tr>

            <tr v-if="roomTypes.length === 0">
              <td colspan="3" class="text-center p-4 text-gray-400">
                No room types found
              </td>
            </tr>
          </tbody>

        </table>

      </div>

    </div>

  </div>
</template>
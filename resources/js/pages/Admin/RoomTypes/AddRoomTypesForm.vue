<template>
    <div class="bg-white p-6 rounded shadow">
      <h2 class="text-xl font-semibold mb-4 text-gray-700">
        {{ formMode === 'create' ? 'Add Room Type' : 'Edit Room Type' }}
      </h2>
  
      <div class="flex flex-col gap-4">
  
        <!-- Name -->
        <div>
          <label class="block mb-1 font-medium text-gray-600">Room Type Name</label>
          <input
            v-model="form.name"
            type="text"
            class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
          />
        </div>
  
        <!-- Buttons -->
        <div class="flex gap-2 justify-end mt-4">
          <button
            @click="save"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            :disabled="loading"
          >
            {{ loading ? 'Saving...' : 'Save' }}
          </button>
  
          <button
            @click="resetForm"
            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400"
          >
            Reset
          </button>
        </div>
  
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, watch } from 'vue'
  import axios from 'axios'
  
  interface RoomType {
    id?: string
    name: string
  }
  
  const props = defineProps<{ initial?: RoomType | null }>()
  const emit = defineEmits<{ (e: 'saved', data: RoomType): void }>()
  
  const formMode = ref<'create' | 'edit'>('create')
  const loading = ref(false)
  
  const form = ref<RoomType>({
    name: ''
  })
  
  // Watch for edit mode
  watch(
    () => props.initial,
    (val) => {
      if (val) {
        form.value = { ...val }
        formMode.value = 'edit'
      } else {
        resetForm()
      }
    },
    { immediate: true }
  )
  
  // Save
  async function save() {
    loading.value = true
    try {
      let response
  
      if (formMode.value === 'create') {
        response = await axios.post('/api/room-types', form.value)
      } else {
        response = await axios.put(`/api/room-types/${form.value.id}`, form.value)
      }
  
      emit('saved', response.data)
      resetForm()
    } catch (err) {
      console.error(err)
      alert('Failed to save room type')
    } finally {
      loading.value = false
    }
  }
  
  // Reset
  function resetForm() {
    form.value = { name: '' }
    formMode.value = 'create'
  }
  </script>
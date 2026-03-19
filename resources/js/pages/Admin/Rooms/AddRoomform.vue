<template>
    <div class="bg-white p-6 rounded shadow">
      <h2 class="text-xl font-semibold mb-4 text-gray-700">
        {{ formMode === 'create' ? 'Add Room' : 'Edit Room' }}
      </h2>
  
      <div class="flex flex-col gap-4">
  
        <!-- Room Title -->
        <div>
          <label class="block mb-1 font-medium text-gray-600">Room Title</label>
          <input
            v-model="form.room_title"
            type="text"
            class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
          />
        </div>
  
        <!-- Image Upload -->
        <div>
          <label class="block mb-1 font-medium text-gray-600">Room Image</label>
          <input type="file" @change="handleFile" class="w-full" />
          <div v-if="previewImage" class="mt-2">
            <img :src="previewImage" class="h-32 w-32 object-cover rounded border" />
          </div>
        </div>
  
        <!-- Room Type -->
        <div>
          <label class="block mb-1 font-medium text-gray-600">Room Type</label>
          <input
            v-model="form.room_type"
            type="text"
            class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
          />
        </div>
  
        <!-- Description -->
        <div>
          <label class="block mb-1 font-medium text-gray-600">Description</label>
          <textarea
            v-model="form.description"
            class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
          ></textarea>
        </div>
  
        <!-- Price -->
        <div>
          <label class="block mb-1 font-medium text-gray-600">Price</label>
          <input
            v-model.number="form.price"
            type="number"
            class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
          />
        </div>
  
        <!-- Wifi -->
        <div class="flex items-center gap-2">
          <input type="checkbox" v-model="form.wifi" id="wifi" class="accent-blue-500" />
          <label for="wifi" class="text-gray-600">Wifi Available</label>
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
  
  interface Room {
    id?: string
    room_title: string
    image?: string | null
    room_type: string
    description: string
    price: number
    wifi: boolean
  }
  
  const props = defineProps<{ initial?: Room | null }>()
  const emit = defineEmits<{ (e: 'saved', room: Room): void }>()
  
  const formMode = ref<'create' | 'edit'>('create')
  const loading = ref(false)
  const file = ref<File | null>(null)
  const previewImage = ref<string | null>(null)
  
  const form = ref<Room>({
    room_title: '',
    image: null,
    room_type: '',
    description: '',
    price: 0,
    wifi: false,
  })
  
  // Watch initial for edit mode
  watch(
    () => props.initial,
    (val) => {
      if (val) {
        form.value = { ...val }
        formMode.value = 'edit'
        previewImage.value = val.image ? `/storage/${val.image}` : null
        file.value = null
      } else {
        resetForm()
      }
    },
    { immediate: true }
  )
  
  // Handle file input
  function handleFile(event: Event) {
    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
      file.value = target.files[0]
      previewImage.value = URL.createObjectURL(file.value)
    }
  }
  
  // Save room
  async function save() {
    loading.value = true
    try {
      const formData = new FormData()
      formData.append('room_title', form.value.room_title)
      formData.append('room_type', form.value.room_type)
      formData.append('description', form.value.description)
      formData.append('price', String(form.value.price))
      formData.append('wifi', form.value.wifi ? '1' : '0')
      if (file.value) formData.append('image', file.value)
  
      let response
      if (formMode.value === 'create') {
        response = await axios.post('/api/rooms', formData)
      } else {
        response = await axios.post(
          `/api/rooms/${form.value.id}?_method=PUT`,
          formData
        )
      }
  
      emit('saved', response.data)
      resetForm()
    } catch (err) {
      console.error(err)
      alert('Failed to save room')
    } finally {
      loading.value = false
    }
  }
  
  // Reset form
  function resetForm() {
    form.value = {
      room_title: '',
      image: null,
      room_type: '',
      description: '',
      price: 0,
      wifi: false,
    }
    previewImage.value = null
    file.value = null
    formMode.value = 'create'
  }
  </script>
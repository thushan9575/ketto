<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'

interface Room {
  id?: string
  room_title: string
  image?: string | null
  room_type_id: string
  description: string
  price: number
  wifi: boolean
}

interface RoomType {
  id: string
  name: string
}

const props = defineProps<{ initial?: any }>()
const emit = defineEmits(['saved'])

const formMode = ref<'create' | 'edit'>('create')
const roomTypes = ref<RoomType[]>([])
const file = ref<File | null>(null)
const previewImage = ref<string | null>(null)

const form = ref<Room>({
  room_title: '',
  image: null,
  room_type_id: '',
  description: '',
  price: 0,
  wifi: false,
})

watch(
  () => props.initial,
  (val) => {
    if (val) {
      form.value = {
        ...val,
        room_type_id: val.room_type_id || val.room_type?.id || ''
      }
      formMode.value = 'edit'
      previewImage.value = val.image ? `/storage/${val.image}` : null
    } else {
      resetForm()
    }
  },
  { immediate: true }
)

async function fetchRoomTypes() {
  const res = await axios.get('/api/room-types')
  roomTypes.value = res.data
}

onMounted(fetchRoomTypes)

function handleFile(e: Event) {
  const target = e.target as HTMLInputElement
  if (target.files && target.files[0]) {
    file.value = target.files[0]
    previewImage.value = URL.createObjectURL(file.value)
  }
}

async function save() {
  const formData = new FormData()
  formData.append('room_title', form.value.room_title)
  formData.append('room_type_id', form.value.room_type_id)
  formData.append('description', form.value.description)
  formData.append('price', String(form.value.price))
  formData.append('wifi', form.value.wifi ? '1' : '0')
  if (file.value) formData.append('image', file.value)

  if (formMode.value === 'create') {
    await axios.post('/api/rooms', formData)
  } else {
    await axios.post(`/api/rooms/${form.value.id}?_method=PUT`, formData)
  }

  emit('saved')
  resetForm()
}

function resetForm() {
  form.value = {
    room_title: '',
    image: null,
    room_type_id: '',
    description: '',
    price: 0,
    wifi: false,
  }
  previewImage.value = null
  file.value = null
  formMode.value = 'create'
}
</script>

<template>
  <div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-xl font-semibold mb-6 text-center text-gray-700">
      {{ formMode === 'create' ? 'Add Room' : 'Edit Room' }}
    </h2>

    <div class="flex flex-col gap-4">
      <div>
        <label class="text-gray-600">Room Title</label>
        <input v-model="form.room_title" class="w-full border px-3 py-2 rounded mt-1" />
      </div>

      <div>
        <label class="text-gray-600">Image</label>
        <input type="file" @change="handleFile" class="mt-1" />
        <img v-if="previewImage" :src="previewImage" class="h-20 mt-2 rounded object-cover" />
      </div>

      <div>
        <label class="text-gray-600">Room Type</label>
        <select v-model="form.room_type_id" class="w-full border px-3 py-2 rounded mt-1">
          <option value="">Select Room Type</option>
          <option v-for="type in roomTypes" :key="type.id" :value="type.id">
            {{ type.name }}
          </option>
        </select>
      </div>

      <div>
        <label class="text-gray-600">Description</label>
        <textarea v-model="form.description" class="w-full border px-3 py-2 rounded mt-1"></textarea>
      </div>

      <div>
        <label class="text-gray-600">Price</label>
        <input type="number" v-model="form.price" class="w-full border px-3 py-2 rounded mt-1" />
      </div>

      <div class="flex items-center gap-2 mt-2">
        <input type="checkbox" v-model="form.wifi" />
        <label class="text-gray-600">Wifi</label>
      </div>

      <div class="flex gap-4 justify-center mt-4">
        <button
          @click="save"
          class="px-6 py-2 bg-black text-white rounded hover:bg-gray-800"
        >
          Save
        </button>
        <button
          @click="resetForm"
          class="px-6 py-2 bg-gray-300 text-black rounded hover:bg-gray-400"
        >
          Reset
        </button>
      </div>
    </div>
  </div>
</template>
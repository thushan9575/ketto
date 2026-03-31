<script setup lang="ts">
import { ref, watch } from 'vue'
import axios from 'axios'

interface RoomType {
  id?: string
  name: string
}

const props = defineProps<{ initial?: RoomType | null }>()
const emit = defineEmits(['saved'])

const formMode = ref<'create' | 'edit'>('create')
const loading = ref(false)

const form = ref<RoomType>({
  name: ''
})

// Watch edit mode
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
    if (formMode.value === 'create') {
      await axios.post('/api/room-types', form.value)
    } else {
      await axios.put(`/api/room-types/${form.value.id}`, form.value)
    }

    emit('saved')
    resetForm()

  } catch (err) {
    alert('Failed to save')
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

<template>
  <div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-lg font-semibold mb-4 text-gray-700">
      {{ formMode === 'create' ? 'Add Room Type' : 'Edit Room Type' }}
    </h2>

    <div class="flex flex-col gap-4">

      <!-- NAME -->
      <div>
        <label class="text-sm text-gray-600">Room Type Name</label>
        <input
          v-model="form.name"
          class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-black"
        />
      </div>

      <!-- BUTTONS -->
      <div class="flex gap-2 justify-end">

        <button
          @click="save"
          :disabled="loading"
          class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800"
        >
          {{ loading ? 'Saving...' : 'Save' }}
        </button>

        <button
          @click="resetForm"
          class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
        >
          Reset
        </button>

      </div>

    </div>

  </div>
</template>
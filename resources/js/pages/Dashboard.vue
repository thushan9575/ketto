<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'

// ✅ Page props
const page = usePage()
const auth_user = page.props.auth?.user || null

// Loading and banner
const loading = ref(true)
const currentBanner = ref(0)
const banners = ref([
  '/images/banner1.jpg',
  '/images/banner2.jpg',
  '/images/banner3.jpg'
])

// Rooms
const rooms = ref([
  { id: 1, name: 'Luxury Bedroom', desc: 'Modern luxury room with sea view', image: '/images/room1.jpg' },
  { id: 2, name: 'Family Room', desc: 'Perfect room for family stay', image: '/images/room2.jpg' },
  { id: 3, name: 'Deluxe Suite', desc: 'Premium suite with luxury design', image: '/images/room3.jpg' }
])

// Gallery
const gallery = ref([
  '/images/gallery1.jpg',
  '/images/gallery2.jpg',
  '/images/gallery3.jpg',
  '/images/gallery4.jpg'
])

// Booking
const arrival = ref('')
const departure = ref('')

// Contact
const contact = ref({ name: '', email: '', phone: '', message: '' })

// Computed user
const authUser = computed(() => auth_user)

// Logout
const logout = () => {
    router.post('/logout')
}

// Banner rotation
const nextBanner = () => {
  currentBanner.value++
  if (currentBanner.value >= banners.value.length) currentBanner.value = 0
}

onMounted(() => {
  setTimeout(() => (loading.value = false), 1500)
  setInterval(nextBanner, 4000)
})

// Methods
const bookRoom = () => alert('Room booked!')
const sendMessage = () => alert('Message sent!')
</script>

<template>
  <div>
    <!-- LOADER -->
    <div v-if="loading" class="text-center p-10">
      <img src="/images/loading.gif" width="80" />
    </div>

    <div v-else>
      <!-- HEADER -->
      <header class="bg-black text-white p-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
          <img src="/images/logo.png" width="120" />

          <nav class="flex items-center gap-4">
            <a href="#" class="text-white">Home</a>
            <a href="#" class="text-white">About</a>
            <a href="#" class="text-white">Rooms</a>
            <a href="#" class="text-white">Gallery</a>
            <a href="#" class="text-white">Blog</a>
            <a href="#" class="text-white">Contact</a>

            <!-- Guest -->
            <template v-if="!authUser">
              <Link href="/login" class="px-4 py-2 border border-white rounded">Login</Link>
              <Link href="/register" class="px-4 py-2 bg-white text-black rounded">Register</Link>
            </template>

            <!-- Authenticated User -->
            <template v-else>
              <button @click="logout" class="px-4 py-2 border border-white rounded bg-transparent">
                Logout
              </button>
            </template>
          </nav>
        </div>
      </header>

      <!-- BANNER -->
      <section>
        <img :src="banners[currentBanner]" class="w-full h-[500px] object-cover" />
      </section>

      <!-- BOOKING -->
      <section class="py-16 bg-gray-100 text-center">
        <h2 class="text-3xl mb-6">Book a Room Online</h2>
        <form @submit.prevent="bookRoom" class="flex flex-wrap justify-center gap-4">
          <input type="date" v-model="arrival" class="p-2 border rounded" />
          <input type="date" v-model="departure" class="p-2 border rounded" />
          <button class="px-6 py-2 bg-black text-white rounded">Book Now</button>
        </form>
      </section>

      <!-- ABOUT -->
      <section class="py-16 max-w-6xl mx-auto flex flex-wrap gap-8 items-center">
        <div class="flex-1">
          <h2 class="text-2xl font-semibold">About Us</h2>
          <p class="mt-4">
            Luxury hotel experience with modern rooms, relaxing environment,
            and premium services for travelers and families.
          </p>
        </div>
        <div class="flex-1">
          <img src="/images/about.png" class="w-full rounded" />
        </div>
      </section>

      <!-- ROOMS -->
      <section class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto">
          <h2 class="text-center text-3xl mb-10">Our Rooms</h2>
          <div class="flex flex-wrap gap-6 justify-center">
            <div v-for="room in rooms" :key="room.id" class="w-72 bg-white rounded shadow overflow-hidden">
              <img :src="room.image" class="w-full h-44 object-cover" />
              <div class="p-4">
                <h3 class="font-semibold">{{ room.name }}</h3>
                <p>{{ room.desc }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- GALLERY -->
      <section class="py-16 max-w-6xl mx-auto">
        <h2 class="text-center text-3xl mb-10">Gallery</h2>
        <div class="flex flex-wrap gap-4 justify-center">
          <img v-for="g in gallery" :key="g" :src="g" class="w-56 h-36 object-cover rounded" />
        </div>
      </section>

      <!-- CONTACT -->
      <section class="py-16 bg-gray-100">
        <div class="max-w-md mx-auto text-center">
          <h2 class="text-2xl mb-6">Contact Us</h2>
          <form @submit.prevent="sendMessage" class="flex flex-col gap-3">
            <input v-model="contact.name" placeholder="Name" class="p-2 border rounded" />
            <input v-model="contact.email" placeholder="Email" class="p-2 border rounded" />
            <input v-model="contact.phone" placeholder="Phone" class="p-2 border rounded" />
            <textarea v-model="contact.message" placeholder="Message" class="p-2 border rounded"></textarea>
            <button class="px-6 py-2 bg-black text-white rounded">Send</button>
          </form>
        </div>
      </section>

      <!-- FOOTER -->
      <footer class="bg-black text-white p-6 text-center">
        <p>© 2026 Keto Hotel</p>
      </footer>
    </div>
  </div>
</template>
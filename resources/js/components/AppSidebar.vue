<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'

import { computed } from 'vue'

import {
  SidebarProvider
} from '@/components/ui/sidebar'

// Auth user
const page = usePage()
const user = computed(() => page.props.auth?.user)

// Menu
const menu = [
  { title: 'Dashboard', href: '/admin/dashboard' },
  { title: 'Rooms', href: '/admin/rooms' },
  { title: 'Room Types', href: '/admin/room-types' },
  { title: 'Bookings', href: '/admin/bookings' },
]

// Active check
const isActive = (href: string) => {
  return window.location.pathname.startsWith(href)
}
</script>

<template>
  <SidebarProvider>
    <div class="flex min-h-screen w-full">

      <!-- 🔥 CUSTOM BLACK SIDEBAR -->
      <aside class="w-64 bg-black text-white flex flex-col">

        <!-- LOGO -->
        <div class="p-4 border-b border-gray-800 flex items-center gap-2">
          <img src="/images/logo.png" class="w-28" />
        </div>

        <!-- MENU -->
        <nav class="flex-1 p-4 space-y-2">

          <Link
            v-for="item in menu"
            :key="item.href"
            :href="item.href"
            class="block px-4 py-2 rounded-lg transition"
            :class="isActive(item.href)
              ? 'bg-white text-black font-semibold'
              : 'hover:bg-gray-800'"
          >
            {{ item.title }}
          </Link>

        </nav>

        <!-- FOOTER USER -->
        <div class="p-4 border-t border-gray-800 text-sm text-gray-300">
          <p class="font-semibold text-white">{{ user?.name }}</p>
          <p class="text-xs">Admin</p>
        </div>

      </aside>

      <!-- 🔥 CONTENT -->
      <div class="flex-1 bg-gray-100 min-w-0">
        <main class="p-6 w-full">
          <slot />
        </main>
      </div>

    </div>
  </SidebarProvider>
</template>

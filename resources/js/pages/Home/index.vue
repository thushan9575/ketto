<template>
    <div>
    
    <!-- LOADER -->
    <div v-if="loading" style="text-align:center;padding:40px">
    <img src="/public/images/loading.gif" width="80">
    </div>
    
    <div v-else>
    
    <!-- HEADER -->
    <header style="background:#111;color:white;padding:15px">
    <div style="max-width:1200px;margin:auto;display:flex;justify-content:space-between;align-items:center">
    
    <img src="/public/images/logo.png" width="120">
    
    <nav style="display:flex;align-items:center">
    
    <a href="#" style="color:white;margin:10px">Home</a>
    <a href="#" style="color:white;margin:10px">About</a>
    <a href="#" style="color:white;margin:10px">Room</a>
    <a href="#" style="color:white;margin:10px">Gallery</a>
    <a href="#" style="color:white;margin:10px">Blog</a>
    <a href="#" style="color:white;margin:10px">Contact</a>
    
    <!-- GUEST -->
    <template v-if="!authUser">
    
    <Link href="/login"
    style="color:white;margin-left:20px;padding:6px 14px;border:1px solid white;border-radius:4px;text-decoration:none">
    Login
    </Link>
    
    <Link href="/register"
    style="background:#fff;color:#111;margin-left:10px;padding:6px 14px;border-radius:4px;text-decoration:none">
    Register
    </Link>
    
    </template>
    
    <!-- AUTH USER -->
    <template v-else>
    
    <Link
    href="/logout"
    method="post"
    as="button"
    style="color:white;margin-left:20px;padding:6px 14px;border:1px solid white;border-radius:4px;background:none">
    Logout
    </Link>
    
    </template>
    
    </nav>
    </div>
    </header>
    
    
    <!-- BANNER -->
    <section>
    <img
    :src="banners[currentBanner]"
    style="width:100%;height:500px;object-fit:cover">
    </section>
    
    
    <!-- BOOKING -->
    <section style="padding:60px;background:#f5f5f5;text-align:center">
    
    <h2 style="margin-bottom:30px">Book a Room Online</h2>
    
    <form @submit.prevent="bookRoom">
    
    <div style="display:flex;justify-content:center;gap:20px;flex-wrap:wrap">
    
    <div>
    <label>Arrival</label><br>
    <input type="date" v-model="arrival" style="padding:10px;width:200px">
    </div>
    
    <div>
    <label>Departure</label><br>
    <input type="date" v-model="departure" style="padding:10px;width:200px">
    </div>
    
    </div>
    
    <button
    style="padding:12px 25px;margin-top:20px;background:#111;color:white;border:none;border-radius:5px">
    Book Now
    </button>
    
    </form>
    
    </section>
    
    
    <!-- ABOUT -->
    <section style="padding:60px;max-width:1200px;margin:auto;display:flex;gap:40px;align-items:center;flex-wrap:wrap">
    
    <div style="flex:1">
    
    <h2>About Us</h2>
    
    <p style="margin-top:15px">
    Luxury hotel experience with modern rooms, relaxing environment,
    and premium services for travelers and families.
    </p>
    
    </div>
    
    <div style="flex:1">
    
    <img src="/public/images/about.png" style="width:100%;border-radius:10px">
    
    </div>
    
    </section>
    
    
    <!-- ROOMS -->
    <section style="padding:60px;background:#fafafa">
    
    <div style="max-width:1200px;margin:auto">
    
    <h2 style="text-align:center;margin-bottom:40px"> Rooms</h2>
    
    <div style="display:flex;gap:30px;flex-wrap:wrap;justify-content:center">
    
    <div
    v-for="room in rooms"
    :key="room.id"
    style="width:280px;background:white;border-radius:10px;overflow:hidden;box-shadow:0 3px 10px rgba(0,0,0,0.1)">
    
    <img :src="room.image" style="width:100%;height:180px;object-fit:cover">
    
    <div style="padding:15px">
    
    <h3>{{room.name}}</h3>
    
    <p>{{room.desc}}</p>
    
    </div>
    
    </div>
    
    </div>
    
    </div>
    
    </section>
    
    
    <!-- GALLERY -->
    <section style="padding:60px">
    
    <div style="max-width:1200px;margin:auto">
    
    <h2 style="text-align:center;margin-bottom:40px">Gallery</h2>
    
    <div style="display:flex;flex-wrap:wrap;gap:20px;justify-content:center">
    
    <img
    v-for="g in gallery"
    :key="g"
    :src="g"
    style="width:220px;height:150px;object-fit:cover;border-radius:8px">
    
    </div>
    
    </div>
    
    </section>
    
    
    <!-- CONTACT -->
    <section style="padding:60px;background:#f5f5f5">
    
    <div style="max-width:600px;margin:auto;text-align:center">
    
    <h2>Contact Us</h2>
    
    <form @submit.prevent="sendMessage" style="margin-top:30px">
    
    <input
    placeholder="Name"
    v-model="contact.name"
    style="width:100%;padding:10px;margin-bottom:10px">
    
    <input
    placeholder="Email"
    v-model="contact.email"
    style="width:100%;padding:10px;margin-bottom:10px">
    
    <input
    placeholder="Phone"
    style="width:100%;padding:10px;margin-bottom:10px">
    
    <textarea
    placeholder="Message"
    style="width:100%;padding:10px;margin-bottom:10px"></textarea>
    
    <button
    style="padding:12px 25px;background:#111;color:white;border:none;border-radius:5px">
    Send
    </button>
    
    </form>
    
    </div>
    
    </section>
    
    
    <!-- FOOTER -->
    <footer style="background:#111;color:white;padding:25px;text-align:center">
    
    <p>© 2026 Keto Hotel</p>
    
    </footer>
    
    </div>
    
    </div>
    </template>
    
    
    
    <script>
    import { Link } from '@inertiajs/vue3'
    
    export default {
   
    
    props:{
    auth_user:Object
    },
    
    components:{
    Link
    },
    
    data(){
    
    return{
    
    loading:true,
    arrival:"",
    departure:"",
    currentBanner:0,
    
    banners:[
    "/images/banner1.jpg",
    "/images/banner2.jpg",
    "/images/banner3.jpg"
    ],
    
    rooms:[
    {
    id:1,
    name:"Luxury Bedroom",
    desc:"Modern luxury room with sea view",
    image:"/images/room1.jpg"
    },
    {
    id:2,
    name:"Family Room",
    desc:"Perfect room for family stay",
    image:"/images/room2.jpg"
    },
    {
    id:3,
    name:"Deluxe Suite",
    desc:"Premium suite with luxury design",
    image:"/images/room3.jpg"
    }
    ],
    
    gallery:[
    "/images/gallery1.jpg",
    "/images/gallery2.jpg",
    "/images/gallery3.jpg",
    "/public/images/gallery4.jpg"
    ],
    
    contact:{
    name:"",
    email:""
    }
    
    }
    
    },
    
    computed:{
    authUser(){
    return this.auth_user
    }
    },
    
    mounted(){
    
    setTimeout(()=>{
    this.loading=false
    },1500)
    
    setInterval(()=>{
    this.nextBanner()
    },4000)
    
    },
    
    methods:{
    
    nextBanner(){
    
    this.currentBanner++
    
    if(this.currentBanner>=this.banners.length){
    this.currentBanner=0
    }
    
    },
    
    bookRoom(){
    alert("Room booked!")
    },
    
    sendMessage(){
    alert("Message sent!")
    }
    
    }
    
    }
    </script>
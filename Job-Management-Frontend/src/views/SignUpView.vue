<template> 
   <section class="bg-green-100"> 
    <div class="container mx-auto max-w-xl py-20"> 
        <form @submit.prevent="handleRegistration" class="bg-white shadow-md rounded-lg px-10 py-8 mb-8"> 
            <h2 class="text-center font-bold text-2xl px-2 py-2">Create Account</h2>
            <div v-if="errorMessage" class="text-red-700 rounded border border-red-300 mb-4 p-3 ">
                {{ errorMessage }}
            </div>

            <div class="mb-4"> 
                <label class="px-2 block text-gray-700 font-bold text-sm" for="name">Name</label>
                <input
                v-model="form.name"
                id="name"
                type="text"
                placeholder="e.g. Alice"
                class="border rounded w-full appearance-none shadow px-2 py-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                />
            </div>
            <div class="mb-4"> 
                <label class="px-2 block text-gray-700 font-bold text-sm" for="email">Email</label>
                <input
                v-model="form.email"
                id="email"
                type="email"
                placeholder="example@gmail.com"
                class="border rounded w-full appearance-none shadow px-2 py-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                />
            </div>
            <div class="mb-4"> 
                <label class="px-2 block text-gray-700 font-bold text-sm" for="password">Password</label>
                <input
                v-model="form.password"
                id="password"
                type="password"
                placeholder="password must be at least 5 characters long"
                class="border rounded w-full appearance-none shadow px-2 py-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                />
            </div>
            <div class="flex items-center justify-center"> 
                <button class="font-bold bg-green-700 hover:bg-green-900 text-white my-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline"> 
                    Sign Up
                </button>
            </div>
        </form>

    </div>
   </section>
</template>


<script setup> 
import { reactive, ref } from 'vue';
import axios from 'axios';
import router from '@/router';
import { useToast } from 'vue-toastification';


const toast = useToast();

const errorMessage = ref('');
const form = reactive({
    name: '',
    email: '',
    password: '',
});


const handleRegistration = async()=>{
    try{
        const response = await axios.post("http://127.0.0.1:8000/api/users/", form);
        toast.success('User Registered');
        router.push('/login');
    }catch(error){
        errorMessage.value = error.response.data.message;
        toast.error('Registration Failed');
        console.log(error);
    }
}
</script>
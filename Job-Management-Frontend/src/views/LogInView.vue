<template> 
    <section class="bg-green-100"> 
        <div class="container mx-auto max-w-xl py-20"> 
            <form @submit.prevent="handleLogin" class="bg-white shadow-md rounder-md px-10 py-10 mb-8">
                <h2 class="text-center font-bold text-2xl px-2 py-2">Log In</h2>
                <div class="mb-4 px-2"> 
                    <label class="px-2 block text-gray-700 font-bold text-sm mb-2" for="email">Email</label>
                    <input
                    v-model="form.email"
                    id="email"
                    type="email"
                    placeholder="Put your email address here"
                    class="border rounded w-full appearance-none shadow px-2 py-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    />
                </div>
                <div class="mb-4 px-2"> 
                    <label class="px-2 block text-gray-700 font-bold text-sm mb-2" for="password">Password</label>
                    <input
                    v-model="form.password"
                    id="password"
                    type="password"
                    placeholder="password must be at least 5 characters long"
                    class="border rounded w-full appearance-none shadow px-2 py-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    />
                </div>
            
                <div class="flex items-center justify-center"> 
                    <button class="font-bold bg-green-700 hover:bg-green-900 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline"> 
                        Log In
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>

<script setup> 
import { reactive, ref } from 'vue';
import router from '@/router';
import { useToast } from 'vue-toastification';
import axios from 'axios';

const toast = useToast();


const form = reactive({
    email: '',
    password: '',

});

const handleLogin = async()=>{
    try{
        const response = await axios.post('http://127.0.0.1:8000/api/login/', form);
        // localStorage.setItem({
        //     'token':response.data.access_token
        // });
        localStorage.setItem('token', response.data.access_token);
        toast.success('Logged in successfully');
        router.push('/');
    }catch(error){
        console.error('An error occurend while Log In', error);
        toast.error('Login Failed');
    }
}
</script>
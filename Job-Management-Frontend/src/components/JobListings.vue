<template>
    <div> 
        <section class="bg-blue-50 px-4 py-10"> 
            <div class="container-xl lg:container m-auto"> 
                <h2 class="text-3xl font-bold text-green-500 mb-6 text-center">
                    {{ isLoggedIn? 'Your Job Posts': 'Browse Jobs' }}
                </h2>
                <!-- Spinner -->
                 <div v-if="state.isLoading" class="text-center text-gray-600 py-6">
                    <PulseLoader/>
                </div>
                <div v-else-if="state.jobs.length===0" class="text-center text-gray-600 py-6"> 
                    No Jobs found
                </div>
                <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <JobListing 
                    v-for="job in state.jobs.slice(0, limit || state.jobs.length)" 
                    :key="job.id" 
                    :job="job"
                    > 
                    </JobListing>
                </div>
            </div>   
        </section>
        <section v-if="showButton" class="m-auto my-10 px-6 max-w-lg">
            <RouterLink
            to="/jobs"
            class="bg-gray-500 hover:bg-gray-700 block text-white text-center px-4 py-4 rounded-md"
            >
                View All Jobs
            </RouterLink>
        </section>
    </div>
</template>

<script setup>

import { ref, defineProps, onMounted, reactive, computed } from 'vue';
import JobListing from './JobListing.vue';
import { RouterLink } from 'vue-router';
import axios from 'axios';
import { data } from 'autoprefixer';
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import { useAuthStore } from '@/stores/auth';


const authStore = useAuthStore();

const state = reactive({
    jobs: [],
    isLoading: true, 
});

const isLoggedIn = computed(()=> authStore.isLoggedIn);



onMounted(async()=>{
    try {
        const baseUrl = 'http://127.0.0.1:8000/api';
        const endpoint = isLoggedIn.value ? '/user/posts' : '/posts';
        const url = `${baseUrl}${endpoint}`;

        const config = {
            headers: isLoggedIn.value ? { 'Authorization': `Bearer ${authStore.token}` } : {}
        };

        const response = await axios.get(url, config);
        state.jobs = response.data.data || response.data; 
    }catch(error){
        console.error('Error fetching the jobs', error);
    }finally{
        state.isLoading= false;
    }
});

defineProps({
    limit:Number,
    showButton:{
        type:Boolean,
        default: false,
    }
});

</script>

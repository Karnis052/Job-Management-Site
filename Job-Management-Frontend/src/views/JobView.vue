<template> 
   
    <div v-if="state.isLoading" class="text-center text-gray-600 py-6">
            <PulseLoader/>
    </div>
    <section v-else  class="bg-green-100">  
        <BackButton/>
        <div class="container m-auto py-10 px-6"> 
            <div class="grid grid-cols-1 w-full md:grid-cols-70/30 gap-6">  
                <main>
                    <div class="bg-white shadow-md rounded-md p-6 text-center md:text-left "> 
                        <div class="text-gray-700 mb-4">{{ state.job.type }}</div>
                        <h2 class="text-3xl mb-4 font-bold"> {{ state.job.title }} </h2>
                        <div class="flex align-middle justify-center md:justify-start mb-4"> 
                            <i class="pi pi-map-marker text-xl text-orange-700 mr-2"> </i>
                            <p class="text-orange-500">{{ state.job.location }}</p>
                        </div>
                    </div>
                    <div class="bg-white shadow-md rounded-md p-6 text-center md:text-left mt-6"> 
                        <h3 class="text-green-900 font-bold text-lg mb-6"> Job Description </h3>
                        <p class="mb-4">
                            {{ state.job.description }}
                        </p>
                        <h3 class="text-green-900 font-bold text-lg mb-2"> Salary </h3>
                        <p class="mb-4">{{ state.job.salary }}/ Year</p>
                    </div>
                    <div class="my-10 flex justify-center"> 
                        <RouterLink 
                        v-if="!canManageJob"
                        :to="`/jobs/${state.job.id}/application`"
                        class="bg-green-500 rounded-md text-white font-bold items-center px-3 py-3 hover:bg-green-700 focus:shadow-outline focus:outline-none text-center"
                        > 
                                Apply Job
                        </RouterLink>

                        <RouterLink 
                         v-else
                        :to="`/jobs/${state.job.id}/applicants`"
                        class="bg-green-500 rounded-md text-white font-bold items-center px-3 py-3 hover:bg-green-700 focus:shadow-outline focus:outline-none text-center"
                        > 
                                View Applications
                        </RouterLink>

                    </div>
                    
                </main>

                 <aside>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-bold mb-4">Company Info</h3>
                        <h2 class="text-2xl mb-2"> {{ state.job.company_name }}</h2>
                        <p class="my-2"> 
                           {{ state.job.company_description }}
                        </p>
                        <hr class="my-4"/>
                        <h3 class="text-xl">Contact Email:</h3>

                        <p class="my-2 bg-green-100 p-2 font-bold">
                            {{ state.job.company_contact_email }}
                        </p>

                        <h3 class="text-xl">Contact Phone:</h3>
                        <p class="my-2 bg-green-100 p-2 font-bold">{{ state.job.company_contact_phone }}</p>
                    </div>

                    <div v-if="canManageJob" class="bg-white mt-6 rounded-md px-4 py-4"> 
                        <h2 class="text-2xl font-bold mb-3">Manage Job</h2>
                        <RouterLink 
                        :to="`/jobs/${state.job.id}/edit/`"
                        class="bg-green-500 rounded-full w-full block font-bold focus:outline-none focus:shadow-outline mt-4 px-4 py-2 hover:bg-green-700 text-white text-center"> 
                            Edit Job
                        </RouterLink>
                        <button   @click="deleteJob"
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline mt-4 block"
                        >
                            Delete Job
                        </button>
                    </div>

                </aside>
            </div>
        </div>
    </section>
</template>

<script setup>
import { RouterLink, useRoute, useRouter } from 'vue-router';
import PulseLoader from 'vue-spinner/src/PulseLoader.vue';
import { reactive, onMounted, ref, computed } from 'vue';
import axios from 'axios';
import BackButton from '@/components/BackButton.vue';
import { useToast } from 'vue-toastification';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const router = useRouter();
const toast = useToast();
const authStore = useAuthStore();

const jobId = route.params.id;
const state = reactive({
    job: {},
    isLoading: true
});

const canManageJob = computed(()=>{
    return authStore.isLoggedIn  && Number(authStore.userId) === Number(state.job.user_id);
});

const deleteJob = async()=>{
    try{
        const confirm  = window.confirm('Are you sure you want to delete this job?');
        if(confirm){
            await axios.delete(`http://127.0.0.1:8000/api/posts/${jobId}`);
            toast.success("Job Deleted Successfully");
            router.push('/jobs')
        }
    }catch(error){
        console.error('Error deleting job', error);
        toast.error('Job not deleted');
    }
}
onMounted(async()=>{
    try{
        const response = await axios.get(`http://127.0.0.1:8000/api/posts/${jobId}`);
        state.job = response.data.data;
        
       
    }catch(error){
        console.error('Error fetching job ', error);
    }finally{
        state.isLoading = false;
    }
});
</script>

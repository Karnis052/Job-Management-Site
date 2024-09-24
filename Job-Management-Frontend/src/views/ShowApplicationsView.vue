<template>
    <section class="bg-green-100">
        <div class="py-3 px-2"> 
            <h1 class="text-2xl text-center font-bold mb-4">Applications for Post</h1>
            <div v-if="state.isLoading" class="text-center">
                <p>Loading applications...</p>
            </div>
            <div v-else-if="state.error" class="text-red-500">
                {{ state.error }}
            </div>
            <div v-else-if="state.applications.length === 0" class="text-gray-500">
                No applications found for this post.
            </div>
            <div v-else class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left font-md text-sm uppercase tracking-wider">First Name</th>
                            <th class="px-6 py-3 text-left font-md text-sm uppercase tracking-wider">Last Name</th>
                            <th class="px-6 py-3 text-left font-md text-sm uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left font-md text-sm uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left font-md text-sm uppercase tracking-wider">Major</th>
                            <th class="px-6 py-3 text-left font-md text-sm uppercase tracking-wider">University</th>
                            <th class="px-6 py-3 text-left font-md text-sm uppercase tracking-wider">Resume</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="application in state.applications" :key="application.id">
                            <td class="px-6 py-3 whitespace-nowrap">{{ application.firstName }}</td>
                            <td class="px-6 py-3 whitespace-nowrap">{{ application.lastName }}</td>
                            <td class="px-6 py-3 whitespace-nowrap">{{ application.email }}</td>
                            <td class="px-6 py-3 whitespace-nowrap">{{ application.phone }}</td>
                            <td class="px-6 py-3 whitespace-nowrap">{{ application.major || 'N/A' }}</td>
                            <td class="px-6 py-3 whitespace-nowrap">{{ application.university || 'N/A' }}</td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <a
                                v-if="application.resume_path"
                                :href="getResumeUrl(application.resume_path)"
                                target="_blank"
                                class="text-blue-600 hover:text-blue-800 text-sm"
                                > 
                                    View Resume
                                </a>
                                <span v-else> No Resume</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="my-8 flex justify-center">  
                <button @click="downloadResume"
                            class="bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded-full text-center"
                >
                            Download All Resume
                </button>
            </div>
        </div>
    </section>
</template>

<script setup>
import axios from 'axios';
import { reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const postId = route.params.id;

const state = reactive({
    applications: [],
    isLoading:true,
    error: null,
})

const getResumeUrl = (resume_path)=>{
    const baseUrl = 'http://127.0.0.1:8000';
    return `${baseUrl}/storage/${resume_path}`;
}

const downloadResume = async()=>{
    try{
        const response = await axios.get(`http://127.0.0.1:8000/api/posts/${postId}/download-resumes`, {
            responseType: 'blob', 
        });

        // Create a URL for the binary data
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
      
        link.setAttribute('download', `resumes.zip`);
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild();

    }catch(error)
    {
        console.error('Error while downloading', error);
    }
}


onMounted(async()=>{
    try{
        const response = await axios.get(`http://127.0.0.1:8000/api/posts/${postId}/applications`);
        state.applications = response.data.data.data;
    }catch(error){
        console.error('Error fetching applications:', error);
        state.error = 'Failed to load applications. Please try again later.';
    }finally{
        state.isLoading = false;
    }
});

</script>

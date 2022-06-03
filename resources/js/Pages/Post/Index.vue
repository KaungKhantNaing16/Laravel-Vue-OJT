<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Post Index
                <Link 
                    class="float-right" 
                    :href="route('posts.create')"
                    v-if="$page.props.permission.posts.create">
                    <JetButton>Create</JetButton>
                </Link>
            </h2>            
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex justify-between">
                        <JetInput
                            type="text"
                            class="placeholder:italic placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm block m-4 w-60"
                            v-model="form.search"
                            placeholder="Search posts ..."
                        />
                        <form @submit.prevent="submit" class="mt-5 mr-4">
                            <input type="file" @input="uploadForm.file = $event.target.files[0]" class="text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-violet-50 file:text-violet-700
                            hover:file:bg-violet-100
                            "/>
                            <button type="submit" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Upload</button>
                            <button @click="exportFile" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Export</button>
                        </form>   
                    </div>

                    <div class="flex flex-col">
                        <div class="my-2 overflow-x-auto sm:mx-6 lg:mx-8">
                            <div class="py-2 align-middle inline-block min-w-full ms:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divided-y divide-gray-200">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ">ID</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ">Title</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ">Created At</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider ">Updated At</th>
                                                <th scope="col" class="relative px-6 py-3">
                                                    <span class="sr-only">Edit</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divided-y divided-gray-200">
                                            <tr v-if="!posts.data.length">
                                                <td class="p-4 text-center text-gray-900" colspan="5">
                                                    No data
                                                </td>
                                            </tr>
                                            <tr v-for="post in posts.data" :key="post.id">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ post.id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ post.title }}
                                                </td>
                                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ post.created_at }}
                                                </td>
                                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ post.updated_at }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <Link :href="route('posts.show', post.id)" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-800" v-if="post.can.view">Show</Link>
                                                    <Link :href="route('posts.edit', post.id)" class="text-yellow-400 hover:text-white border border-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900"  v-if="post.can.update">Edit</Link>
                                                    <button @click="deletePost(post.id)" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900" type="button"  v-if="post.can.delete">Delete</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <JetPagination class="m-5" :links="posts.links" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </AppLayout>
    
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { reactive, watchEffect } from "vue";
import { Link } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { pickBy } from 'lodash';
import { ref } from "vue";
import JetButton from '@/Jetstream/Button';
import JetInput from '@/Jetstream/Input';
import JetPagination from '@/Components/Pagination';

export default {
    components: {
        AppLayout,
        Link,
        JetButton,
        JetInput,
        JetPagination, 
    },

    props: {
        posts: Object,
        filters: Object
    },

    setup(props) {
        const form = reactive({
            search: props.filters.search,
            page: props.filters.page,
        });

        const uploadForm = useForm({
            file: '',
        })

        function submit() {
            uploadForm.post('posts/import', props.posts.user_id)
        }

        watchEffect(() => {
            const query = pickBy(form);

            Inertia.replace(
                route('posts.index', Object.keys(query).length ? query : {})
            );
        });

        const deletePost = (postId) => {
            const result = confirm("Are you sure want to delete?");
            if (result) {
                    Inertia.delete(route("posts.destroy", postId), {
                    preserveScroll: true,
                });
            }
        };

        const exportFile = () => {
            Inertia.post(route('export'))
        }

        return {
            form,
            deletePost,
            submit,
            exportFile
        }
    },
}
</script>

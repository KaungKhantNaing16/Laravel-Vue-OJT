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
                    <jet-input
                    type="text"
                    class="block m-4 w-60"
                    v-model="form.search"
                    placeholder="Search post ..."
                    />
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
                                                    <Link :href="route('posts.show', post.id)" class="text-indigo-600 hover:text-indigo-900" v-if="post.can.view">Show</Link>
                                                    <Link :href="route('posts.edit', post.id)" class="ml-2 text-indigo-600 hover:text-indigo-900"  v-if="post.can.update">Edit</Link>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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
import { Inertia } from '@inertiajs/inertia';
import JetButton from '@/Jetstream/Button';
import JetInput from '@/Jetstream/Input';
import JetPagination from '@/Components/Pagination';

export default {
    components: {
        AppLayout,
        Link,
        JetButton,
        JetInput,
        JetPagination
    },

    props: {
        posts: Object,
        filters: Object
    },

    setup(props) {
        const form = reactive({
            search: props.filters.search,
        });

        // watchEffect(() => {
        //     const query = pickBy(form);

        //     Inertia.replace(
        //         route('users.index', Object.keys(query).length ? query : {})
        //     );
        // });

        return {
            form
        }
    }
}
</script>
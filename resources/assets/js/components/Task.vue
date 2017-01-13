<template>
    <a :href="url">
    <div class="panel panel-default">
        <p style="font-size:xx-large" class="panel-heading text-center">{{title}}</p>
            <div class="panel-body">
                {{body}}
            </div>        
        </div>
    </a>
</template>

<script>
    export default{
        props:['title', 'url', 'body']
    }
</script>

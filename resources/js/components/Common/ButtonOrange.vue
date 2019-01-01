<template>
	<a href="#" :class="btnClass" :title="btnTitle"></a>
</template>

<script>
  export default {
  		props: ['btnClass', 'btnTitle']
  	}
</script>
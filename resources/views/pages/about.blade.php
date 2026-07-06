@extends('layouts.app')

@section('title', 'About Us | Ikat Ethnic')

@section('content')
  <!-- Page Header -->
  <header class="pt-32 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full text-center">
    <h1 class="font-body text-4xl md:text-5xl font-medium text-ink mb-4">Our Heritage</h1>
    <p class="text-muted text-sm max-w-2xl mx-auto font-light leading-relaxed">
      Discover the story behind Ikat Ethnic, our mission, and the master artisans who weave history into every thread.
    </p>
  </header>

  <!-- Main Content: About Us -->
  <main class="grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 flex flex-col gap-24">
    
    <!-- Story Section -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
      <div class="relative overflow-hidden group h-[500px] rounded-sm">
        <img
          src="{{ asset('/storage/products/kain-tenun-toraja.webp') }}"
          alt="About Image"
          class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
      </div>
      <div class="flex flex-col justify-center">
        <h2 class="font-body text-3xl font-medium text-ink mb-6">Preserving Ancient Wisdom</h2>
        <p class="text-muted text-sm font-light leading-relaxed mb-6">
          Ikat Ethnic was founded with a singular vision: to honor and preserve the rich textile heritage of the Indonesian archipelago. Our journey began in the remote villages of Sumba, where we witnessed master weavers transforming natural threads into intricate stories of culture, spirituality, and nature.
        </p>
        <p class="text-muted text-sm font-light leading-relaxed mb-6">
          We work directly with weaving communities across Indonesia, ensuring that traditional techniques like Ikat, Songket, and Batik are passed down to future generations. By providing a global platform, we empower these artisans to sustain their livelihoods while sharing their magnificent art with the world.
        </p>
        <p class="text-gold text-sm font-medium tracking-wide">
          Every piece is a testament to patience, skill, and deep-rooted tradition.
        </p>
      </div>
    </section>
  </main> 
@endsection

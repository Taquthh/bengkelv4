<!DOCTYPE html>
<html class="{{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        {{-- Enhanced Print Styles --}}
        <style>
            /* Print-specific styles */
            @media print {
                /* Hide everything by default */
                body * {
                    visibility: hidden;
                }
                
                /* Show only print content */
                #print-content,
                #print-content * {
                    visibility: visible;
                }
                
                /* Reset print content positioning */
                #print-content {
                    position: absolute !important;
                    left: 0 !important;
                    top: 0 !important;
                    width: 100% !important;
                    height: auto !important;
                    margin: 0 !important;
                    padding: 20px !important;
                    background: white !important;
                    color: black !important;
                    font-size: 12px !important;
                    line-height: 1.4 !important;
                }
                
                /* Print page setup */
                @page {
                    size: A4;
                    margin: 1cm;
                }
                
                /* Table styles for print */
                table {
                    width: 100% !important;
                    border-collapse: collapse !important;
                    margin: 0 !important;
                    page-break-inside: avoid;
                }
                
                th, td {
                    border: 1px solid #000 !important;
                    padding: 8px !important;
                    font-size: 11px !important;
                    line-height: 1.3 !important;
                }
                
                thead {
                    display: table-header-group;
                }
                
                tfoot {
                    display: table-footer-group;
                }
                
                tr {
                    page-break-inside: avoid;
                }
                
                /* Headers */
                h1 {
                    font-size: 20px !important;
                    margin-bottom: 10px !important;
                }
                
                h2 {
                    font-size: 16px !important;
                    margin-bottom: 8px !important;
                }
                
                h3 {
                    font-size: 14px !important;
                    margin-bottom: 6px !important;
                }
                
                /* Colors for print */
                .text-green-700 {
                    color: #15803d !important;
                }
                
                .text-red-700 {
                    color: #b91c1c !important;
                }
                
                .bg-slate-100 {
                    background-color: #f1f5f9 !important;
                }
                
                /* Hide elements that shouldn't print */
                .print\\:hidden {
                    display: none !important;
                }
                
                /* Force show print elements */
                .print\\:block {
                    display: block !important;
                }
                
                .print\\:fixed {
                    position: fixed !important;
                }
            }
            
            /* Screen styles for better print preview */
            #print-content {
                font-family: Arial, sans-serif;
            }
            
            /* Body class for printing state */
            body.printing {
                overflow: hidden;
            }
            
            body.printing * {
                visibility: hidden;
            }
            
            body.printing #print-content,
            body.printing #print-content * {
                visibility: visible;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 mt-16">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
                @livewireScripts
            </main>
        </div>
    </body>

</html>

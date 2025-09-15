@props(['property'])

<div class="relative w-full max-w-full bg-black/40 backdrop-blur-2xl border border-white/10 rounded-3xl p-4 sm:p-6 lg:p-8"
     x-data="mortgageCalculator({{ $property->price ?? 0 }})"
     x-init="init()">
     
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center mb-4">
            <svg class="w-6 h-6 mr-3 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-xl sm:text-2xl font-serif font-medium text-white">Mortgage Calculator</h3>
        </div>
        <p class="text-white/70 text-sm sm:text-base">Calculate your monthly payments and see how much you can afford.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Input Section -->
        <div class="space-y-4">
            <!-- Home Price -->
            <div>
                <label class="block text-white/80 text-sm font-medium mb-2">Home Price</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary text-lg font-medium">$</span>
                    <input 
                        type="number" 
                        x-model.number="homePrice"
                        @input="calculate()"
                        class="w-full pl-8 pr-4 py-3 bg-black/60 border border-white/20 rounded-2xl text-white placeholder-white/50 focus:border-secondary focus:ring-2 focus:ring-secondary/30 focus:outline-none transition-all duration-300"
                        placeholder="350000"
                        min="0"
                        step="1000"
                    >
                </div>
            </div>

            <!-- Down Payment -->
            <div>
                <label class="block text-white/80 text-sm font-medium mb-2">Down Payment</label>
                <div class="grid grid-cols-2 gap-3">
                    <!-- Amount -->
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary text-lg font-medium">$</span>
                        <input 
                            type="number" 
                            x-model.number="downPaymentAmount"
                            @input="updateDownPaymentFromAmount()"
                            class="w-full pl-8 pr-4 py-3 bg-black/60 border border-white/20 rounded-2xl text-white placeholder-white/50 focus:border-secondary focus:ring-2 focus:ring-secondary/30 focus:outline-none transition-all duration-300"
                            placeholder="70000"
                            min="0"
                            step="1000"
                        >
                    </div>
                    <!-- Percentage -->
                    <div class="relative">
                        <input 
                            type="number" 
                            x-model.number="downPaymentPercent"
                            @input="updateDownPaymentFromPercent()"
                            class="w-full pl-4 pr-8 py-3 bg-black/60 border border-white/20 rounded-2xl text-white placeholder-white/50 focus:border-secondary focus:ring-2 focus:ring-secondary/30 focus:outline-none transition-all duration-300"
                            placeholder="20"
                            min="0"
                            max="100"
                            step="0.1"
                        >
                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-secondary text-lg font-medium">%</span>
                    </div>
                </div>
            </div>

            <!-- Interest Rate & Loan Term -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Interest Rate -->
                <div>
                    <label class="block text-white/80 text-sm font-medium mb-2">Interest Rate</label>
                    <div class="relative">
                        <input 
                            type="number" 
                            x-model.number="interestRate"
                            @input="calculate()"
                            class="w-full pl-4 pr-8 py-3 bg-black/60 border border-white/20 rounded-2xl text-white placeholder-white/50 focus:border-secondary focus:ring-2 focus:ring-secondary/30 focus:outline-none transition-all duration-300"
                            placeholder="7.5"
                            min="0"
                            max="20"
                            step="0.01"
                        >
                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-secondary text-lg font-medium">%</span>
                    </div>
                </div>

                <!-- Loan Term -->
                <div>
                    <label class="block text-white/80 text-sm font-medium mb-2">Loan Term</label>
                    <select 
                        x-model.number="loanTermYears"
                        @change="calculate()"
                        class="w-full px-4 py-3 bg-black/60 border border-white/20 rounded-2xl text-white focus:border-secondary focus:ring-2 focus:ring-secondary/30 focus:outline-none transition-all duration-300"
                    >
                        <option value="15">15 years</option>
                        <option value="20">20 years</option>
                        <option value="25">25 years</option>
                        <option value="30" selected>30 years</option>
                    </select>
                </div>
            </div>

            <!-- Optional Fields Toggle -->
            <div class="border-t border-white/10 pt-4">
                <button 
                    @click="showAdvanced = !showAdvanced"
                    class="flex items-center w-full text-left text-white/80 hover:text-secondary transition-colors duration-300"
                >
                    <svg class="w-4 h-4 mr-2 transform transition-transform duration-300" :class="{ 'rotate-180': showAdvanced }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    <span class="text-sm font-medium">Advanced Options</span>
                </button>
                
                <!-- Advanced Fields -->
                <div x-show="showAdvanced" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0" class="mt-4 space-y-4 overflow-hidden">
                    
                    <!-- Property Taxes -->
                    <div>
                        <label class="block text-white/80 text-sm font-medium mb-2">Property Taxes (Annual)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary text-lg font-medium">$</span>
                            <input 
                                type="number" 
                                x-model.number="propertyTaxes"
                                @input="calculate()"
                                class="w-full pl-8 pr-4 py-3 bg-black/60 border border-white/20 rounded-2xl text-white placeholder-white/50 focus:border-secondary focus:ring-2 focus:ring-secondary/30 focus:outline-none transition-all duration-300"
                                placeholder="4200"
                                min="0"
                                step="100"
                            >
                        </div>
                    </div>

                    <!-- Home Insurance -->
                    <div>
                        <label class="block text-white/80 text-sm font-medium mb-2">Home Insurance (Annual)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary text-lg font-medium">$</span>
                            <input 
                                type="number" 
                                x-model.number="homeInsurance"
                                @input="calculate()"
                                class="w-full pl-8 pr-4 py-3 bg-black/60 border border-white/20 rounded-2xl text-white placeholder-white/50 focus:border-secondary focus:ring-2 focus:ring-secondary/30 focus:outline-none transition-all duration-300"
                                placeholder="1200"
                                min="0"
                                step="50"
                            >
                        </div>
                    </div>

                    <!-- PMI -->
                    <div>
                        <label class="block text-white/80 text-sm font-medium mb-2">PMI (Monthly)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary text-lg font-medium">$</span>
                            <input 
                                type="number" 
                                x-model.number="pmi"
                                @input="calculate()"
                                class="w-full pl-8 pr-4 py-3 bg-black/60 border border-white/20 rounded-2xl text-white placeholder-white/50 focus:border-secondary focus:ring-2 focus:ring-secondary/30 focus:outline-none transition-all duration-300"
                                placeholder="200"
                                min="0"
                                step="10"
                            >
                        </div>
                        <p class="mt-1 text-xs text-white/50">Usually required if down payment is less than 20%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div class="space-y-6">
            <!-- Monthly Payment Breakdown -->
            <div class="bg-black/60 border border-white/20 rounded-3xl p-6 backdrop-blur-sm">
                <h4 class="text-lg font-medium text-secondary mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                    Monthly Payment Breakdown
                </h4>
                
                <div class="space-y-3">
                    <!-- Principal & Interest -->
                    <div class="flex justify-between items-center py-2 border-b border-white/10">
                        <span class="text-white/70">Principal & Interest:</span>
                        <span class="text-white font-medium text-lg font-mono" x-text="formatCurrency(monthlyPI)"></span>
                    </div>

                    <!-- Property Taxes -->
                    <div x-show="propertyTaxes > 0" class="flex justify-between items-center py-2 border-b border-white/10">
                        <span class="text-white/70">Property Taxes:</span>
                        <span class="text-white font-medium font-mono" x-text="formatCurrency(propertyTaxes / 12)"></span>
                    </div>

                    <!-- Home Insurance -->
                    <div x-show="homeInsurance > 0" class="flex justify-between items-center py-2 border-b border-white/10">
                        <span class="text-white/70">Home Insurance:</span>
                        <span class="text-white font-medium font-mono" x-text="formatCurrency(homeInsurance / 12)"></span>
                    </div>

                    <!-- PMI -->
                    <div x-show="pmi > 0" class="flex justify-between items-center py-2 border-b border-white/10">
                        <span class="text-white/70">PMI:</span>
                        <span class="text-white font-medium font-mono" x-text="formatCurrency(pmi)"></span>
                    </div>

                    <!-- Total Monthly Payment -->
                    <div class="flex justify-between items-center py-3 bg-secondary/20 rounded-2xl px-4 border border-secondary/30">
                        <span class="text-secondary font-medium text-lg">Total Monthly Payment:</span>
                        <span class="text-secondary font-bold text-xl font-mono" x-text="formatCurrency(totalMonthlyPayment)"></span>
                    </div>
                </div>
            </div>

            <!-- Loan Summary -->
            <div class="bg-black/60 border border-white/20 rounded-3xl p-6 backdrop-blur-sm">
                <h4 class="text-lg font-medium text-secondary mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Loan Summary
                </h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-white/5 rounded-2xl border border-white/10">
                        <div class="text-white/60 text-xs uppercase tracking-wide font-light mb-1">Loan Amount</div>
                        <div class="text-white text-lg font-bold font-mono" x-text="formatCurrency(loanAmount)"></div>
                    </div>
                    
                    <div class="text-center p-4 bg-white/5 rounded-2xl border border-white/10">
                        <div class="text-white/60 text-xs uppercase tracking-wide font-light mb-1">Total Interest</div>
                        <div class="text-white text-lg font-bold font-mono" x-text="formatCurrency(totalInterest)"></div>
                    </div>
                </div>

                <div class="mt-4 text-center p-4 bg-secondary/10 rounded-2xl border border-secondary/20">
                    <div class="text-secondary/80 text-xs uppercase tracking-wide font-light mb-1">Total Amount Paid</div>
                    <div class="text-secondary text-xl font-bold font-mono" x-text="formatCurrency(totalAmountPaid)"></div>
                </div>
            </div>

            <!-- Affordability Indicator -->
            <div class="bg-gradient-to-r from-secondary/20 via-secondary/10 to-secondary/20 border border-secondary/30 rounded-3xl p-6 backdrop-blur-sm">
                <h4 class="text-lg font-medium text-secondary mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Affordability Guide
                </h4>
                <div class="text-sm text-white/80 leading-relaxed">
                    <p class="mb-2">For comfortable affordability, your total monthly housing payment should typically be:</p>
                    <div class="bg-black/40 rounded-2xl p-4 border border-white/10">
                        <div class="flex justify-between items-center">
                            <span>28% of gross monthly income:</span>
                            <span class="font-bold text-secondary font-mono" x-text="formatCurrency(totalMonthlyPayment / 0.28)"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function mortgageCalculator(propertyPrice) {
    return {
        // Input values
        homePrice: propertyPrice || 350000,
        downPaymentAmount: 0,
        downPaymentPercent: 20,
        interestRate: 7.5,
        loanTermYears: 30,
        propertyTaxes: 0,
        homeInsurance: 0,
        pmi: 0,
        
        // UI state
        showAdvanced: false,
        
        // Calculated values
        loanAmount: 0,
        monthlyPI: 0,
        totalMonthlyPayment: 0,
        totalInterest: 0,
        totalAmountPaid: 0,
        
        init() {
            // Set default down payment based on home price
            this.updateDownPaymentFromPercent();
            this.calculate();
        },
        
        updateDownPaymentFromPercent() {
            this.downPaymentAmount = Math.round((this.homePrice * this.downPaymentPercent) / 100);
            this.calculate();
        },
        
        updateDownPaymentFromAmount() {
            if (this.homePrice > 0) {
                this.downPaymentPercent = Math.round((this.downPaymentAmount / this.homePrice) * 100 * 10) / 10;
            }
            this.calculate();
        },
        
        calculate() {
            // Calculate loan amount
            this.loanAmount = this.homePrice - this.downPaymentAmount;
            
            if (this.loanAmount <= 0 || this.interestRate <= 0 || this.loanTermYears <= 0) {
                this.monthlyPI = 0;
                this.totalInterest = 0;
                this.totalAmountPaid = 0;
                this.totalMonthlyPayment = 0;
                return;
            }
            
            // Convert annual rate to monthly and years to months
            const monthlyRate = (this.interestRate / 100) / 12;
            const numberOfPayments = this.loanTermYears * 12;
            
            // Calculate monthly payment using standard mortgage formula
            if (monthlyRate === 0) {
                // No interest case
                this.monthlyPI = this.loanAmount / numberOfPayments;
            } else {
                this.monthlyPI = this.loanAmount * (monthlyRate * Math.pow(1 + monthlyRate, numberOfPayments)) / (Math.pow(1 + monthlyRate, numberOfPayments) - 1);
            }
            
            // Calculate total interest and amount paid
            this.totalAmountPaid = this.monthlyPI * numberOfPayments;
            this.totalInterest = this.totalAmountPaid - this.loanAmount;
            
            // Calculate total monthly payment including taxes, insurance, PMI
            this.totalMonthlyPayment = this.monthlyPI + (this.propertyTaxes / 12) + (this.homeInsurance / 12) + this.pmi;
        },
        
        formatCurrency(amount) {
            if (isNaN(amount) || amount === null || amount === undefined) {
                return '$0';
            }
            return '$' + Math.round(amount).toLocaleString();
        }
    }
}
</script>

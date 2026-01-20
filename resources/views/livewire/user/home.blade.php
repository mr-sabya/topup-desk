<div>
    
    <!-- Back Button -->
    @if($step > 1)
    <div class="mb-3 animate__animated animate__fadeIn">
        <button wire:click="goBack"
            class="btn btn-light rounded-pill px-3 btn-sm border-0 shadow-sm d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Back
        </button>
    </div>
    @endif

    <!-- STEP 1: CATEGORY -->
    @if($step == 1)
    <div class="animate__animated animate__fadeInRight">
        <h4 class="fw-bold mb-1 text-dark">Services</h4>
        <p class="text-muted small mb-4">Select what you want to pay for</p>

        <div class="row g-3">
            @foreach($this->categories as $cat)
            <div class="col-md-3 col-6 text-center">
                <div wire:click="selectCategory({{ $cat->id }})"
                    class="card-app active-scale p-3 d-flex flex-column align-items-center justify-content-center"
                    style="aspect-ratio: 1/1; cursor: pointer; background: linear-gradient(135deg, #ffffff, #f8fafc);">
                    <div class="gradient-icon-bg mb-2">
                        <img src="{{ asset('storage/'.$cat->icon) }}"
                            style="width: 55px; height: 55px; object-fit: contain;">
                    </div>
                    <span class="fw-600 text-dark" style="font-size: 14px;">{{ $cat->name }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- STEP 2: PROVIDER -->
    @if($step == 2)
    <div class="animate__animated animate__fadeInRight">
        <h4 class="fw-bold mb-1 text-dark">Operator</h4>
        <p class="text-muted small mb-4">Choose your provider for {{ $this->selectedCategory->name }}</p>

        <div class="row g-3">
            @foreach($this->providers as $prov)
            <div class="col-12">
                <div wire:click="selectProvider({{ $prov->id }})"
                    class="card-app p-3 d-flex align-items-center active-scale"
                    style="cursor: pointer; background: linear-gradient(135deg, #ffffff, #f1f5f9);">
                    <img src="{{ asset('storage/'.$prov->logo) }}" class="rounded-circle border"
                        width="48" height="48">
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold text-dark">{{ $prov->name }}</h6>
                        <small class="text-muted">Instant Payment</small>
                    </div>
                    <i class="bi bi-chevron-right ms-auto text-muted fs-5"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- STEP 3: FORM -->
    @if($step == 3)
    <div class="animate__animated animate__fadeInRight">
        <div class="card-app p-4 mb-4">
            <div class="text-center mb-4">
                <img src="{{ asset('storage/'.$this->selectedProvider->logo) }}"
                    class="rounded-circle border mb-2 shadow-sm" width="65" height="65">
                <h5 class="fw-bold mb-0 text-dark">{{ $this->selectedProvider->name }}</h5>
                <small class="text-muted">{{ $this->selectedCategory->name }} Service</small>
            </div>

            @if (session()->has('success'))
            <div class="alert alert-success rounded-4 small py-2 mb-3 text-center">
                {{ session('success') }}
            </div>
            @endif

            <form wire:submit.prevent="submit">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Phone Number</label>
                    <input type="tel" wire:model="phone_number" class="form-control form-control-lg"
                        placeholder="01XXXXXXXXX">
                    @error('phone_number') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Amount</label>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light fw-bold">৳</span>
                        <input type="number" wire:model="amount"
                            class="form-control form-control-lg border-start-0" placeholder="0.00">
                    </div>
                    @error('amount') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Type</label>
                    <div class="d-flex gap-2">
                        <button type="button" wire:click="$set('connection_type', 'Prepaid')"
                            class="btn flex-grow-1 rounded-pill py-2 {{ $connection_type == 'Prepaid' ? 'btn-primary' : 'btn-light' }}">
                            Prepaid
                        </button>
                        <button type="button" wire:click="$set('connection_type', 'Postpaid')"
                            class="btn flex-grow-1 rounded-pill py-2 {{ $connection_type == 'Postpaid' ? 'btn-primary' : 'btn-light' }}">
                            Postpaid
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">
                    <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                    Pay Now
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
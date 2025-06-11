<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 shadow-xl border border-white/20">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400 mb-2">
                        ✏️ Edit Soal
                    </h1>
                    <p class="text-white/70 text-lg">Quiz: {{ $question->quiz->title }}</p>
                    <div class="flex justify-center gap-3 mt-4">
                        <a href="{{ route('admin.quiz.edit', $question->quiz) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 transform hover:scale-105">
                            <i class="fas fa-edit mr-2"></i>Edit Quiz
                        </a>
                        <a href="{{ route('admin.quiz.show', $question->quiz) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-200 transform hover:scale-105">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 shadow-xl border border-white/20">
                <form action="{{ route('admin.questions.update', $question) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Question Type -->
                        <div>
                            <label for="question_type" class="block mb-2 text-white font-semibold">
                                <i class="fas fa-list mr-2 text-blue-400"></i>Tipe Soal
                            </label>
                            <select id="question_type" name="question_type" 
                                    class="w-full p-4 rounded-xl bg-white/10 text-white border border-white/20 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('question_type') border-red-500 @enderror">
                                <option value="multiple_choice" {{ old('question_type', $question->question_type) === 'multiple_choice' ? 'selected' : '' }} class="bg-gray-800">Pilihan Ganda</option>
                                <option value="essay" {{ old('question_type', $question->question_type) === 'essay' ? 'selected' : '' }} class="bg-gray-800">Essay</option>
                                <option value="file_based" {{ old('question_type', $question->question_type) === 'file_based' ? 'selected' : '' }} class="bg-gray-800">File Based</option>
                            </select>
                            @error('question_type')
                                <p class="text-red-300 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Question Text -->
                        <div>
                            <label for="question_text" class="block mb-2 text-white font-semibold">
                                <i class="fas fa-question-circle mr-2 text-green-400"></i>Pertanyaan
                            </label>
                            <textarea id="question_text" name="question_text" rows="4" required
                                      class="w-full p-4 rounded-xl bg-white/10 text-white placeholder-white/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all resize-none @error('question_text') border-red-500 @enderror" 
                                      placeholder="Masukkan pertanyaan...">{{ old('question_text', $question->question_text) }}</textarea>
                            @error('question_text')
                                <p class="text-red-300 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Question Image -->
                        <div>
                            <label for="image" class="block mb-2 text-white font-semibold">
                                <i class="fas fa-image mr-2 text-purple-400"></i>Gambar Soal (opsional)
                            </label>
                            <input type="file" 
                                   class="w-full p-4 rounded-xl bg-white/10 text-white border border-white/20 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all @error('image') border-red-500 @enderror" 
                                   id="image" name="image" accept="image/*">
                            @if($question->image)
                                <div class="mt-4 p-4 bg-white/5 rounded-xl border border-white/10">
                                    <img src="{{ asset('storage/'.$question->image) }}" alt="Current Image" class="w-32 h-20 object-cover rounded-lg shadow-md">
                                    <p class="text-white/60 text-sm mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>Gambar saat ini
                                    </p>
                                </div>
                            @endif
                            @error('image')
                                <p class="text-red-300 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Time Limit -->
                        <div>
                            <label for="time_limit_per_question" class="block mb-2 text-white font-semibold">
                                <i class="fas fa-clock mr-2 text-yellow-400"></i>Batas Waktu per Soal (detik)
                            </label>
                            <input type="number" id="time_limit_per_question" name="time_limit_per_question" min="10" 
                                   class="w-full p-4 rounded-xl bg-white/10 text-white placeholder-white/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all @error('time_limit_per_question') border-red-500 @enderror" 
                                   value="{{ old('time_limit_per_question', $question->time_limit_per_question) }}" 
                                   placeholder="Contoh: 60">
                            <p class="text-white/50 text-sm mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Kosongkan jika tidak ingin ada batas waktu per soal
                            </p>
                            @error('time_limit_per_question')
                                <p class="text-red-300 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Multiple Choice Options (Hanya 4 Pilihan) -->
                        <div id="multiple_choice_section" style="display: {{ old('question_type', $question->question_type) === 'multiple_choice' ? 'block' : 'none' }}">
                            <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                                <i class="fas fa-list-ol mr-2 text-indigo-400"></i>
                                Pilihan Jawaban
                            </h3>
                            <div class="space-y-4">
                                @for($i = 0; $i < 4; $i++)
                                    <div class="bg-white/5 rounded-xl p-4 border border-white/10 hover:bg-white/10 transition-all duration-200">
                                        <div class="flex items-center gap-4">
                                            <div class="flex items-center">
                                                <input type="radio" id="correct_{{ $i }}" name="correct_option" value="{{ $i }}" 
                                                       class="w-5 h-5 text-blue-500 bg-transparent border-2 border-white/30 focus:ring-blue-400 focus:ring-2"
                                                       {{ old('correct_option', array_search($question->correct_answer, $question->options ?? [])) == $i ? 'checked' : '' }}>
                                                <label for="correct_{{ $i }}" class="ml-3 text-white font-medium">
                                                    Pilihan {{ chr(65 + $i) }}
                                                </label>
                                            </div>
                                            <div class="flex-1">
                                                <input type="text" id="options_{{ $i }}" name="options[{{ $i }}]" 
                                                       class="w-full p-3 rounded-lg bg-white/10 text-white placeholder-white/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                                       value="{{ old('options.'.$i, isset($question->options[$i]) ? $question->options[$i] : '') }}"
                                                       placeholder="Masukkan pilihan {{ chr(65 + $i) }}">
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                                
                                <div class="bg-blue-500/20 border border-blue-500/50 rounded-xl p-4 mt-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-info-circle text-blue-400 mt-1 mr-3"></i>
                                        <div>
                                            <h4 class="text-blue-300 font-semibold mb-1">Petunjuk:</h4>
                                            <p class="text-blue-200 text-sm">Pilih radio button di sebelah kiri untuk menandai jawaban yang benar</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Essay/File Based Answer -->
                        <div id="text_answer_section" style="display: {{ old('question_type', $question->question_type) != 'multiple_choice' ? 'block' : 'none' }}">
                            <label for="correct_answer" class="block mb-2 text-white font-semibold">
                                <i class="fas fa-check-circle mr-2 text-green-400"></i>Kunci Jawaban / Panduan Penilaian
                            </label>
                            <textarea class="w-full p-4 rounded-xl bg-white/10 text-white placeholder-white/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all resize-none" 
                                      id="correct_answer" name="correct_answer" rows="4"
                                      placeholder="Masukkan jawaban yang benar atau panduan untuk penilaian">{{ old('correct_answer', $question->correct_answer) }}</textarea>
                            <p class="text-white/50 text-sm mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Untuk soal essay/file, ini bisa berupa kata kunci atau panduan penilaian
                            </p>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-white/20">
                            <a href="{{ route('admin.quiz.show', $question->quiz) }}" 
                               class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-xl font-semibold transition duration-200 transform hover:scale-105">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition duration-200 transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i>Perbarui Soal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('question_type').addEventListener('change', function() {
            const type = this.value;
            const multipleChoiceSection = document.getElementById('multiple_choice_section');
            const textAnswerSection = document.getElementById('text_answer_section');
            
            if (type === 'multiple_choice') {
                multipleChoiceSection.style.display = 'block';
                textAnswerSection.style.display = 'none';
            } else {
                multipleChoiceSection.style.display = 'none';
                textAnswerSection.style.display = 'block';
            }
        });
    </script>
</x-app-layout>
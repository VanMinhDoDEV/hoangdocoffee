@if(isset($toc) && count($toc) > 0)
    <div class="ul-blog-toc {{ $class ?? '' }} {{ isset($collapsed) && $collapsed ? 'collapsed' : '' }} js-toc-container group">
        <div class="ul-blog-toc-header flex justify-between items-center cursor-pointer js-toc-toggle select-none">
            <h4 class="ul-blog-toc-title m-0 border-0 p-0">Mục lục</h4>
            <span class="ul-blog-toc-icon transition-transform duration-300 w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full group-hover:bg-gray-200">
                <i class="flaticon-arrow-down-sign-to-navigate text-[12px]"></i>
            </span>
        </div>
        <div class="ul-blog-toc-content transition-all duration-300 overflow-hidden" style="max-height: 1000px; opacity: 1;">
            <ul class="ul-blog-toc-list mt-3 pt-3 border-t border-gray-200">
                @foreach($toc as $item)
                    <li class="toc-item toc-level-{{ $item['level'] }}">
                        <a href="#{{ $item['id'] }}" class="toc-link relative pl-4 hover:text-primary transition-colors duration-200 block py-1 text-gray-600" data-target="{{ $item['id'] }}">
                            {{ $item['text'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
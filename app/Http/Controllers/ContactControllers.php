<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactControllers
{
    /**
     * 메인 인덱스
     * @param string|null $id 사용자 이름
     * @param int $limit 페이지 당 아이템 개수
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(?string $id = '', int $limit = 5)
    {
        $list = $id ? $this->getList($id, $limit) : [];

        return view('contact', compact('id', 'list'));
    }

    /**
     * 등록
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse\
     */
    public function registration(Request $request)
    {
        $validated = $request->validate([
            'writer' => 'required',
            'name' => 'required',
            'phone_number' => ['required', 'numeric', Rule::unique('contact')->where(function ($query) use ($request) {
                return $query->where('writer', $request->json('writer'))
                    ->where('phone_number', $request->json('phone_number'));
            })]
        ]);

        $contact = Contact::create([
            'writer' => $request->json('writer'),
            'name' => $request->json('name'),
            'phone_number' => $request->json('phone_number')
        ]);

        $query = $this->getList($request->json('writer'));

        return response()->json([
            'contact' => $query,
            'link' => $query->links()->render()
        ]);
    }

    /**
     * 저장
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $compareQuery = Contact::where('idx', $request->json('idx'))
            ->where('writer', $request->json('writer'))
            ->first();

        $validatedArr = [
            'idx' => 'required',
            'writer' => 'required',
            'name' => 'required'
        ];

        //실제 쿼리의 폰넘버와 리퀘스트 폰넘버값 검사에 따른 밸리데이션
        if ($compareQuery['phone_number'] !== $request->json('phone_number')) {
            $validatedArr['phone_number'] = [
                'required', 'numeric', Rule::unique('contact')->where(function ($query) use ($request) {
                    return $query->where('writer', $request->json('writer'))
                        ->where('phone_number', $request->json('phone_number'));
                })
            ];
        } else {
            $validatedArr['phone_number'] = 'required|numeric';
        }

        $validated = $request->validate($validatedArr);

        $compareQuery->update(['name' => $request->json('name'), 'phone_number' => $request->json('phone_number')]);

        return response()->json($compareQuery);
    }

    /**
     * 삭제
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {

        $contact = Contact::where('idx', $request->json('idx'))
            ->where('phone_number', $request->json('phone_number'))
            ->delete();

        $query = $this->getList($request->json('writer'));

        $request->merge(['page' => $request->json('page') ?: 1]);

        return response()->json([
            'contact' => $query,
            'link' => $query->links()->render()
        ]);
    }

    /**
     * 검색
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'select' => 'required',
            'search' => 'required'
        ]);

        $query = $this->getList($request->json('writer'));

        return response()->json($query);
    }

    /**
     * 연락처 리스트 쿼리
     * @param string $id 사용자 이름
     * @param int $limit 페이지 당 아이템 개수
     * @return mixed
     */
    private function getList(string $id = '', int $limit = 5)
    {
        //초깃값 설정
        $request = Request::capture()->json();
        $query['name'] = $request->get('select') === 'name' ? 'Y' : 'N';
        $query['phone_number'] = $request->get('select') === 'phone_number' ? 'Y' : 'N';

        if ($query['name'] !== 'N') {
            $query = Contact::where('writer', $id)
                ->where('name', 'like', $request->get('search') . '%')
                ->orderBy('idx', 'desc')
                ->paginate();
        } else if ($query['phone_number'] !== 'N') {
            $query = Contact::where('writer', $id)
                ->where('phone_number', 'like', $request->get('search') . '%')
                ->orderBy('idx', 'desc')
                ->paginate();
        } else {
            $query = Contact::where('writer', $id)
                ->orderBy('idx', 'desc')
                ->paginate($limit);
        }

        //경로설정
        $query->withPath('/contact/' . $id);

        return $query;
    }
}

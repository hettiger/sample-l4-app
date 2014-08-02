<?php

/**
 * Class FormValidationException
 *
 * This is simplified for you to make things easier.
 * In a real application you could be using something like this: https://github.com/laracasts/validation
 */
class FormValidationException extends Exception {

	/**
	 * @var mixed
	 */
	protected $errors;

	/**
	 * @param string $message
	 * @param mixed  $errors
	 */
	function __construct($message, $errors)
	{
		$this->errors = $errors;

		parent::__construct($message);
	}

	/**
	 * Get form validation errors
	 *
	 * @return mixed
	 */
	public function getErrors()
	{
		return $this->errors;
	}

}

class PostsController extends BaseController {

    /**
     * Post Repository
     *
     * @var Post
     */
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $posts = $this->post->all();

        return View::make('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('posts.create');
    }

	/**
	 * This is simplified for you to make things easier.
	 * In a real application you could be using something like this: https://github.com/laracasts/validation
	 *
	 * @param $formData
	 * @return bool
	 * @throws FormValidationException
	 */
	protected function validate($formData)
	{
		$validation = Validator::make($formData, Post::$rules);

		if ($validation->fails())
		{
			throw new FormValidationException('Validation failed', $validation->getMessageBag());
		}

		return true;
	}

    /**
     * Store a newly created resource in storage.
	 *
     * @return Response
     */
    public function store()
    {
        $input = Input::only('title','body');

		// Uncomment the try + catch blocks to make the ReproduceCept pass.

//		try
//		{
			$this->validate($input);
//		}
//		catch (FormValidationException $exception)
//		{
//			return Redirect::back()
//				->withInput()
//				->withErrors($exception->getErrors())
//				->with('message', 'There were validation errors.');
//		}

		$this->post->create($input);

		return Redirect::route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return View::make('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $post = $this->post->find($id);

        if (is_null($post))
        {
            return Redirect::route('posts.index');
        }

        return View::make('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $input = Input::only('title','body');
        $validation = Validator::make($input, Post::$rules);

        if ($validation->passes())
        {
            $post = $this->post->find($id);
            $post->update($input);

            return Redirect::route('posts.show', $id);
        }

        return Redirect::route('posts.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->post->find($id)->delete();

        return Redirect::route('posts.index');
    }

}
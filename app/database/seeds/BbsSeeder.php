<?php

class BbsSeeder extends Seeder {

	public function run() {

		$this->loadBbs();
	
		
	}
	
	private function loadBbs() {
		$this->loadFiles('plan', Category::planCategory());
		$this->loadFiles('record', Category::recordCategory());
		$this->renewIndex();
	}
	
	private function loadFiles($dir, $category) {
		$gm = User::where('name', '管理員')->first();
		$data['user_id'] = $gm->id;
		$data['zone_id'] = Zone::mainZone()->id;
		$data['category_id'] = $category->id;
				
		foreach (File::files($dir) as $file) {		
			$data = $this->loadFile($file, $data);			
			Article::create($data);
		}	
	}
	
	private function loadFile($file, $data) {
		$data['content'] = File::get($file);
		if (preg_match('/作者  (.*) \(/', $data['content'], $regs)) {
			$author = $regs[1];
		}
		if (preg_match('/標題  (.*)/', $data['content'], $regs)) {
			$data['title'] = $author . ' ' . $regs[1];
		}
		if (preg_match('/時間.*?(\d{4}\/\d{2}\/\d{2}) .*? (\d{2}:\d{2}:\d{2})/', $data['content'], $regs)) {
			$data['created_at'] = new Date($regs[1] . ' ' . $regs[2]);
		} else if (preg_match('/時間  .*?\((.*\d{4})\)/', $data['content'], $regs)) {
			$data['created_at'] = new Date($regs[1]);
		} else if (preg_match('/時間  .{3} (.*\d{4})/', $data['content'], $regs)) {
			$data['created_at'] = new Date($regs[1]);		
		}
		return $data;
	}
	
	private function renewIndex() {
		$articles = Article::where('zone_id', Zone::mainZone()->id)->orderBy('created_at')->get();
		$index = 1;
		foreach ($articles as $article) {
			$article->index = $index++;
			$article->save();
		}
	}
	
}